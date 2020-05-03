# honeymint

Used a MySQL database to store the data entered by the user. All code has been written in HTML, CSS and PHP.

When the user signs up, a connection to the local database is established using PDO and PHP.

All data from the form is stored in the database using POST method.

As soon as the user submits the form, an e-Mail is sent to the user.
I’ve used Mailgun’s SMTP service to send test e-Mails. To generate the e-Mail content, I used Designmodo’s Postcards.

The e-Mail consists of:
• Referral code (as the subject)
• Receipt of entry
• Option to share to Facebook and Twitter

The files uploaded are first stored in a local folder.

Using shell_exec() command, we upload these to a git repository (this is important because otherwise we don’t have a permalink to share to Facebook)
Once you sign up, “thank you” page is shown.

From the email, the user has an option of sharing to Facebook. This opens a new page where the user is prompted to enter their file name with extension. Once that is submitted, the user can press the Share button to share to Facebook.
Facebook share: Attaches submitted file. Twitter share: Doesn’t attach file.
