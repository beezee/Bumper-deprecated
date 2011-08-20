Bumper is a stripped down version of followup.cc and nudgemail that lets you host your own email reminder service.

I loved these services when I found out about them but privacy concerns prevented me from utilizing a 3rd party with my email, even if I only share headers.

Anyone else with similar privacy concerns can now host their own email reminder service with Bumper by following these steps:

1 - Download the source and upload to the root of a domain that you will use for your Bumper app.
2 - Create an empty MySQL database on your host
3 - Go to www.Your-Bumper-Domain.com/setup and run the setup wizard
4 - Login to www.Your-Bumper-Domain and follow the steps on the Configuration tab to create a cron job that checks for reminders, and a catchall email address that points to the email parser.
5 - Look at the "How To Schedule Reminders" tab for how to use your BCC field to have Bumper send emails back to you at your desired time, and start using it!

Just for ease, I've also included the usage instructions below:

How to Set Reminders

Bumper schedules your reminders based on the email address you use to set them.

For example if your catchall domain was bumper.cc, sending an email to thursday@bumper.cc would schedule Bumper to send that email back to you next Thursday. Note that Bumper reads the BCC field for this address, so you must use that field to schedule your reminder. 

This also means you can reply to an email and schedule a reminder at the same time.

The list below contains examples of the six different ways you can format your scheduling request when BCC'ing Bumper to set a reminder.

Date, or date-time of day.

BCC july14@bumper.cc to have the email sent back to you on July 14th (3PM by default.)

BCC september20-3pm@bumper.cc to have the email sent back to you on September 20th at 3pm.


Day of week, or day of week-time of day.

BCC thursday@bumper.cc to have the email sent back to you on Thursday (3PM by default.)

BCC tuesday-9.30am@bumper.cc to have the email sent back to you on Tuesday at 9:30 AM.


Time from now, or time from now + time of day.

BCC 2weeks2days3hours5minutes to have the email sent back to you in 2 weeks, 2days, 3 hours and 5 minutes from now.

BCC 1year2days10minutes to have the email sent back to you in 1 year, 2days and 10 minutes from now.

BCC 2months8am to have the email sent back to you in 2 months at 8 am.

BCC 1week2days12pm to have the email sent back to you in 1 week and 2 days, at 12 noon.

Note that when combining time from now + time of day, you can use years, months, weeks and days + a time of day. Time of day replaces hours and minutes.