#!/usr/bin/python
from dejavu import Dejavu
import sys

if __name__ == '__main__':
    djv = Dejavu()
    djv.fingerprint_file(sys.argv[1],sys.argv[2])
