#!/bin/bash

php ../artisan queue:work >> queue.txt &
php ../artisan schedule:work >> schedule.txt &
