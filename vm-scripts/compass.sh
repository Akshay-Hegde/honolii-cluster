#!/usr/bin/env bash

# Compass project path
scss_path=/vagrant/application/wp-content/themes/theme_name/assets/compass_dir

# First compile the project, so that we get style.css
printf "Compiling Project\n"
compass compile $scss_path

# Now, watch the project for changes
compass watch $scss_path > /dev/null 2>/dev/null &
cid=$! > ./cwatch.pid
printf "Compass is watcing your project - PID: $cid\n"
printf "\n\nDevelopment environment is up and running."
printf "\nOpen the following url in your browser: http://192.168.33.10 (Cmd + Click)"