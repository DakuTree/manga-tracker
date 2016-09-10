#!/bin/sh

S3URI="s3://-/server/"`date +'%Y-%m-%d'`"/"
TMPDIR="/tmp/backup_"`date +'%Y-%m-%d'`

if [ "$EUID" -ne 0 ]; then
	echo "Please run as root"
	exit
fi

#/srv/backupToS3.sh > /srv/backupToS3.txt

echo "Started backup @" `date +'%a %b %e %H:%M:%S %Z %Y'`

#Check if backup folder already exists for some reason..
if [ -d ${TMPDIR} ]; then
	rm -rf ${TMPDIR}
fi

#Create backup folder and move to it
mkdir ${TMPDIR} && cd ${TMPDIR} && echo "Created & moved to TMP dir: "${TMPDIR}

sudo s3cmd sync --recursive --preserve /etc/letsencrypt ${S3URI}"etc/"

sudo tar -zcvf config_files.tar.gz /etc/apache2/apache2.conf /etc/mysql
sudo s3cmd sync --recursive --preserve config_files.tar.gz ${S3URI}

sudo tar -zcvf virualhosts.tar.gz /etc/apache2/sites-available
sudo s3cmd sync --recursive --preserve virualhosts.tar.gz ${S3URI}

#TODO: This should be {dev,trackr}
sudo tar --exclude="/var/log/journal/*" -zcvf logs.tar.gz /var/log /var/www/trackr.moe/dev/public_html/application/logs
sudo s3cmd sync --recursive --preserve logs.tar.gz ${S3URI}

sudo bash -c 'dpkg --get-selections > dpkg.list'
sudo s3cmd sync dpkg.list ${S3URI}

sudo bash -c 'find /var/www -type f -follow -print | gzip > www_filelist.txt.gz'
sudo s3cmd sync www_filelist.txt.gz ${S3URI}

sudo crontab -l > crontab.cron
sudo s3cmd sync crontab.cron ${S3URI}

sudo mkdir mysql_dump
for DB in `mysql --defaults-extra-file=/etc/mysql/conf.d/mysqldump_pwd.cnf -A --skip-column-names  -e "SHOW DATABASES;" | perl -lpe's/^(?:Database|information_schema|performance_schema|test|sys)$//g' | sed '/^$/d'`; do
	sudo mysqldump --defaults-extra-file=/etc/mysql/conf.d/mysqldump_pwd.cnf --hex-blob --routines --triggers -E --single-transaction ${DB} | gzip > mysql_dump/${DB}.sql.gz &
done
wait
sudo s3cmd sync --recursive mysql_dump ${S3URI}

#Cleanup
rm -rf ${TMPDIR}

echo "Finished backup @" `date +'%a %b %e %H:%M:%S %Z %Y'`
