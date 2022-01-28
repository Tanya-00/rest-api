#!/bin/bash

commitRegex='^(\[[A-Z][0-9]+\].+)'
if ! grep -qE "$commitRegex" "$1"; then
    echo "Invalid commit message. Expected: [number of issue] message"
    exit 1
figit