#!/usr/bin/env bash

set -x

count=$(git status -s | wc -l  | tr -d '[:space:]')
if [[ $count -eq 0 ]]; then
  echo "No updates found stopping build"
  circleci-agent step halt
fi