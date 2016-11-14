#!/bin/bash
mv html/assets/node_modules ~/
appcfg.py -A APPNAME -V v1 update .
mv ~/node_modules html/assets/
