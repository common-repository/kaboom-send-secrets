=== Send Secrets By Kaboom ===
Contributors: chriskroon
Tags: Send Secrets, Send Passwords, Secure, Kaboom
Requires at least: 4.0.0
Tested up to: 5.2.3
Requires PHP: 7.0.0
Stable tag: 1.0.4

This plugin makes it possible to send secrets to your clients. You use the shortcode [stand_alone_send_secret], there will appear an input field to send the information to your client.

== Description ==
 
### Send Secrets By Kaboom

This plugin makes it possible to send secrets to your clients. You use the shortcode [stand_alone_send_secret], there will appear an input field to send the information to your client.

### What do you get
* Shortcode to display the sending field [stand_alone_send_secret]
* Once opend cannot be opend again
* Send unlimited secrets to unlimited clients
* Saves the information into your own database
* Secrets in the databsase for more than 4 weeks get deleted automaticly
* Translate every field your customer can potentional see

== Screenshots ==
1. Send Secrets dashboard
2. Send Secrets front end
3. Error when you forgot to enter a secret
4. Error when someone try to open a send secret more than once

=== Installation - From WordPress Admin ===
* Go to 'Plugins > Add new'
* Search for "Send Secrets By Kaboom"
* Hit Install Now, and Activate after that

=== Manual ===
* Upload the `send-secrets-by-kaboom` folder to the `/wp-content/plugins` directory
* Activate through the WordPress admin from 'Plugins > Installed Plugins'

=== After Installation ===
Apply the [stand_alone_send_secret] shortcode on the page you would like to use.
In the dashboard you can translate the buttons. 

== Changelog ==

= 1.0.4 =
* For long secrets the display field will extend

= 1.0.3 =
* Fix: If a robot crawls the page (for example in a messenger app) it doesn't flag it as seen already 

= 1.0.2 =
* Creates database table if plugin get activated

= 1.0.1 =
* Add Kaboom Send Secrets to kaboom.php overview page

= 1.0.0 =
* Version 1.0.0