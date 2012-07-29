#!/bin/bash

if [ -e /etc/redhat_release ]; then
	grep Fedora /etc/redhat_release
	if [ $? -eq 0 ]; then	
		OS="fedora"