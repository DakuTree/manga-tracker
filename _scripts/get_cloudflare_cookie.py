import sys, json
import cfscrape

# Load the data that PHP sent us
try:
    data = json.loads(sys.argv[1])

    cookies, user_agent = cfscrape.get_cookie_string(data['url'], user_agent=data['user_agent'])

    print json.dumps({'cookies': cookies, 'agent': user_agent})
except:
    print "No JSON sent?"
    sys.exit(1)
