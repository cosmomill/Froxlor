This is a fork of Froxlor that adds the ability to separate mailaccounts from addresses.
Loosely based on Yaser Oulabi's syscp-email module for syscp 1.2.16

ATTENTION:
Use this repository only for a new Froxlor installation, or on a Froxlor installation based on this fork.
Use this on your own risk!

==========================
DESCRIPTION
In Froxlor users are given the "email address" as the username/mailbox name. If you login with POP3 or IMAP you use your email address.
If you want other email addresses to receive to this inbox, you create forwarders to the mailbox-email address.

Mailboxes handled different in this Froxlor fork:
A mailbox is given a username by the following scheme: customerp1, customerp2, customerp3, customerp4 ( you can use a different prefix than p, for example pop or mbox etc. ), so that a Mailbox exists in the beginning without having any email-addresses linking to it. Addresses can be added, just like with usual Froxlor, by creating forwarders. This time the forwarders link to the mailbox name ( you can comfortably chose the mailbox from a dop down menu ).

NOTE: If you want to forward an address from a different customer, you can add an forwarder to any address that already forwards to the mailbox, similar to the Froxlor way.

==========================
INSTALLATION

You have to edit the postfix configuration
	If you are using maildrop:

		Add this line to your main.cf
local_transport = maildrop


		Edit master.cf changing:

maildrop  unix  -       n       n       -       -       pipe
  flags=R user=vmail argv=/usr/bin/maildrop -d ${recipient}
 
		to:

maildrop  unix  -       n       n       -       -       pipe
  flags=R user=vmail argv=/usr/bin/maildrop -d ${user}



If you don't want to use maildrop only add this line to the main.cf
local_transport = virtual

Nothing is changed in the maildrop and virtual_mysql config.

