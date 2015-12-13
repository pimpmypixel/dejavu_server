#!/usr/bin/python

import json
import warnings
import sys
import os
import MySQLdb
import MySQLdb.cursors

from dejavu import Dejavu

warnings.filterwarnings("ignore")
db = os.path.abspath("python/database.json")
config = {}

try:
    with open(db) as f:
        config['database'] = json.load(f)
        con = MySQLdb.connect(
            config.get('database').get('host'),
            config.get('database').get('user'),
            config.get('database').get('passwd'),
            config.get('database').get('db'),
            cursorclass=MySQLdb.cursors.DictCursor
        )
        cur = con.cursor()
        cur.execute("SELECT * FROM `configurations` ORDER BY id DESC")
        config['fingerprint'] = cur.fetchone()
        if __name__ == '__main__':
            djv = Dejavu(config)
            djv.fingerprint_directory()

except MySQLdb.Error, e:
    print "Error %d: %s" % (e.args[0], e.args[1])
    sys.exit(1)