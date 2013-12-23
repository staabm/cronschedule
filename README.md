cronschedule
============

My first Silex experiments. App just takes a cronexpression and tells you when the next runs will be scheduled.

Example
=======

Input 
```
"3-59/15 2,6-12 */15 1 2-5"
```

Output

```
First run in aprox. 3 weeks

2014-01-15 02:03:00
                    15 mins
2014-01-15 02:18:00
                    15 mins
2014-01-15 02:33:00
                    15 mins
2014-01-15 02:48:00
                    3 hours
2014-01-15 06:03:00
                    15 mins
2014-01-15 06:18:00
                    15 mins
2014-01-15 06:33:00
                    15 mins
2014-01-15 06:48:00
                    15 mins
2014-01-15 07:03:00
                    15 mins
2014-01-15 07:18:00
                    15 mins
2014-01-15 07:33:00
                    15 mins
2014-01-15 07:48:00
                    15 mins
2014-01-15 08:03:00
                    15 mins
2014-01-15 08:18:00
                    15 mins
2014-01-15 08:33:00
```