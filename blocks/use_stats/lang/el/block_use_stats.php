<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

// Capabilities.
$string['use_stats:addinstance'] = 'Can add an instance'; // Is a @DYNAKEY.
$string['use_stats:myaddinstance'] = 'Can add an instance to My Page'; // Is a @DYNAKEY.
$string['use_stats:seecoursedetails'] = 'Can see detail of all users from his course'; // Is a @DYNAKEY.
$string['use_stats:seegroupdetails'] = 'Can see detail of all users from his groups'; // Is a @DYNAKEY.
$string['use_stats:seeowndetails'] = 'Can see his own detail'; // Is a @DYNAKEY.
$string['use_stats:seesitedetails'] = 'Can see detail of all users'; // Is a @DYNAKEY.
$string['use_stats:view'] = 'Can see stats'; // Is a @DYNAKEY.
$string['use_stats:export'] = 'Can export as pdf (needs trainingsessions report)'; // Is a @DYNAKEY.

// Privacy.
$string['privacy:metadata'] = "The Use Stats Block does not directely store any data belonging to users";

$string['activetrackingparams'] = 'Ενεργές ρυθμίσεις παρακολούθησης';
$string['activities'] = 'Δραστηριότητες';
$string['allowrule'] = 'Να επιτρέπεται η αποστολή κατά τον κανόνα αντιστοίχισης';
$string['allusers'] = 'Ολοι οι χρήστες';
$string['blockdisplay'] = 'Αποκλεισμός συντονισμού οθόνης';
$string['blockname'] = 'Use Stats';
$string['byname'] = 'Από όνομα';
$string['bytimedesc'] = 'Από χρόνο';
$string['cachedef_aggregate'] = 'Συγκεντρωτικά';
$string['capabilitycontrol'] = 'Ικανότητα';
$string['configbacktrackmode'] = 'Λειτουργία πίσω κομματιού';
$string['configbacktrackmode_desc'] = 'Επιλέγει τον τρόπο με τον οποίο επιλέγονται τα μπλοκ κατά την παρακολούθηση των χρόνων.';
$string['configbacktracksource'] = 'Πίσω πηγή κομματιού';
$string['configbacktracksource_desc'] = 'Επιλέγει ποιος λέει στα μπλοκ την αναφορά χρόνου backtracking.';
$string['configcalendarskin'] = 'Τύπος ημερολογίου';
$string['configcalendarskin_desc'] = 'Αλλάζει την εμφάνιση του ημερολογίου.';
$string['configcustomtagselect'] = 'Επιλέξτε για προσαρμοσμένη ετικέτα';
$string['configcustomtagselect_desc'] = 'Αυτό το ερώτημα πρέπει να επιστρέφει ένα μοναδικό αποτέλεσμα ανά γραμμή καταγραφής. αυτό το αποτέλεσμα θα τροφοδοτήσει την προσαρμοσμένη ετικέτα {$a} στήλη.';
$string['configdisplayactivitytimeonly'] = 'Επιλέξτε ποια ώρα αναφοράς θα εμφανιστεί';
$string['configdisplayactivitytimeonly_desc'] = 'Μπορείτε να επιλέξετε ποιος είναι ο χρόνος εκμάθησης αναφοράς για εμφάνιση';
$string['configdisplayothertime'] = 'Εμφανίστε την ώρα "Εκτός μαθήματος"';
$string['configdisplayothertime_desc'] = 'Ορίστηκε, εμφανίζει τη γραμμή μαθημάτων χρόνου "Εκτός μαθήματος"';
$string['configenablecompilecube'] = 'Ενεργοποίηση συλλογής κύβων';
$string['configenablecompilecube_desc'] = 'Όταν ενεργοποιηθεί, οι πρόσθετες διαστάσεις υπολογίζονται χρησιμοποιώντας καθορισμένες επιλογές';
$string['configenrolmentfilter'] = 'Φίλτρο εγγεγραμμένων περιόδων';
$string['configenrolmentfilter_desc'] = 'Εάν είναι ενεργά, τα αρχεία καταγραφής θα αναλυθούν από την πρώτη διαθέσιμη ενεργή ημερομηνία εγγραφής ή το μάθημα θα ξεκινήσει το νωρίτερο. Εάν απενεργοποιηθεί, το ξεκίνημα του μαθήματος θα είναι το μόνο νωρίτερο όριο.';
$string['configfilterdisplayunder'] = 'Οθόνη φίλτρου κάτω';
$string['configfilterdisplayunder_desc'] = 'Εάν δεν είναι κενό, θα εμφανίζονται μόνο οι χρόνοι του μαθήματος πάνω από το καθορισμένο όριο (σε δευτερόλεπτα)';
$string['configfromwhen'] = 'Από ';
$string['configfromwhen_desc'] = 'Περίοδος κατάρτισης (σε ημέρες έως σήμερα) ';
$string['configkeepalivecontrol'] = 'Μέθοδος ελέγχου';
$string['configkeepalivecontrol_desc'] = 'εσωτερικά δεδομένα που χρησιμοποιούνται για τον έλεγχο της δυνατότητας αποστολής';
$string['configkeepalivecontrolvalue'] = 'Όνομα στοιχείου ελέγχου';
$string['configkeepalivecontrolvalue_desc'] = 'θα ταιριάζει με τον κανόνα εάν επιτρέπεται η ικανότητα ή εάν το πεδίο προφίλ δεν έχει τιμή null. Η προεπιλεγμένη ρύθμιση εξαιρεί τους διαχειριστές.';
$string['configkeepalivedelay'] = 'Συνεχής περίοδος συνόδου';
$string['configkeepalivedelay_desc'] = 'Καθυστέρηση ανάμεσα σε δύο ίχνη καταγραφής για συνδεδεμένους χρήστες (δευτερόλεπτα). Διατηρήστε όσο το δυνατόν μεγαλύτερο για να μειώσετε το φορτίο του διακομιστή όταν είναι συνδεδεμένοι πολλοί χρήστες, διατηρώντας παράλληλα τα ίχνη παρακολούθησης.';
$string['configkeepaliveenable'] = 'Ενεργοποίηση συντήρησης';
$string['configkeepaliveenable_desc'] = 'Session keepalive method will send constantly tracking pulses to moodle when a user is viewing a moodle screen on his terminal. Note that this method should be used with care, as potentially measuring inconsistant local behaviour.';
$string['configkeepaliverule'] = 'Αποστολή keepalive εάν';
$string['configkeepaliverule_desc'] = 'Κανόνας για τον έλεγχο της αποστολής ajax';
$string['configlastcompiled'] = 'Τελευταία ημερομηνία καταγραφής αρχείου καταγραφής';
$string['configlastcompiled_desc'] = 'Κατά την αλλαγή αυτής της ημερομηνίας κομματιού, το cron θα υπολογίσει εκ νέου όλα τα αρχεία καταγραφής μετά τη δεδομένη ημερομηνία';
$string['configlastpingcredit'] = 'Πίστωση επιπλέον χρόνου στο τελευταίο ping';
$string['configlastpingcredit_desc'] = 'Αυτό το χρονικό διάστημα (σε λεπτά) θα προστεθεί συστηματικά στο πλήθος χρόνου παρακολούθησης καταγραφής για κάθε φορά που μαντεύεται το κλείσιμο μιας συνεδρίας ή η ασυνέχεια';
$string['configonesessionpercourse'] = 'Μία συνεδρία ανά μάθημα';
$string['configonesessionpercourse_desc'] = 'Όταν είναι ενεργοποιημένη, το stat θα διαιρεί τις περιόδους σύνδεσης κάθε φορά που το κομμάτι αλλάζει την πορεία των τρεχουσών. Εάν απενεργοποιηθεί, μια συνεδρία αντιπροσωπεύει μια ακολουθία εργασίας που μπορεί να χρησιμοποιεί πολλά μαθήματα.';
$string['configthreshold'] = 'Κατώφλι';
$string['configthreshold_desc'] = 'Όριο συνέχειας δραστηριότητας (λεπτά). Πάνω από αυτό το χρονικό διάστημα μεταξύ δύο διαδοχικών κομματιών στο αρχείο καταγραφής, ο χρήστης θεωρείται αποσυνδεδεμένος. Ο αυθαίρετος χρόνος "Last Ping Credit" θα προστεθεί στον αριθμό του.';
$string['credittime'] = '(LTC) ';
$string['datacubing'] = 'Αντιγραφή δεδομένων';
$string['declaredtime'] = 'Δηλωμένη ώρα'; // Is a @DYNAKEY.
$string['denyrule'] = 'Να επιτρέπεται η αποστολή εκτός από τον κανόνα που ταιριάζει';
$string['dimensionitem'] = 'Παρατηρήσιμα μαθήματα';
$string['displayactivitiestime'] = 'Μόνο χρόνος που αποδίδεται σε αποτελεσματικές δραστηριότητες στο μάθημα';
$string['displaycoursetime'] = 'Σε πραγματικό χρόνο του μαθήματος (όλη η ώρα σε όλα τα πλαίσια του μαθήματος)';
$string['emulatecommunity'] = 'Προσομοιώστε την έκδοση της κοινότητας';
$string['emulatecommunity_desc'] = 'Εάν είναι ενεργοποιημένο, η προσθήκη θα συμπεριφέρεται ως έκδοση της κοινότητας. Αυτό μπορεί να χάσει χαρακτηριστικά!';
$string['errornorecords'] = 'Δεν υπάρχουν πληροφορίες εντοπισμού';
$string['eventscount'] = 'Χτυπήματα';
$string['eventusestatskeepalive'] = 'Συνεδρία keep alive';
$string['fixedchoice'] = 'Οι ρυθμίσεις αναγκάστηκαν για την πορεία / ημερομηνία έναρξης λογαριασμού';
$string['fixeddate'] = 'Από μια καθορισμένη ημερομηνία αναφοράς';
$string['from'] = 'Από&ensp;';
$string['fromrange'] = 'Από&ensp;';
$string['go'] = 'Πήγαινε';
$string['hidecourselist'] = 'Απόκρυψη ωρών μαθημάτων';
$string['isfiltered'] = 'Εμφανίζονται μόνο χρόνος άνω των {$a} δευτερολέπτων ';
$string['keepuseralive'] = 'Ο χρήστης {$a} βρίσκεται ακόμη σε περίοδο σύνδεσης';
$string['licenseprovider'] = 'Πάροχος άδειας Pro';
$string['licenseprovider_desc'] = 'Εισαγάγετε εδώ το κλειδί του παροχέα σας';
$string['licensekey'] = 'Κλειδί άδειας Pro';
$string['licensekey_desc'] = 'Εισαγάγετε εδώ το κλειδί άδειας χρήσης προϊόντος που λάβατε από τον παροχέα σας';
$string['loganalysisparams'] = 'Παράμετροι ανάλυσης καταγραφής';
$string['modulename'] = 'Παρακολούθηση δραστηριότητας';
$string['noavailablelogs'] = 'Δεν υπάρχουν διαθέσιμα αρχεία καταγραφής';
$string['onthismoodlefrom'] = ' εδώ από ';
$string['other'] = 'Άλλη εκτός μαθήματος παρουσία';
$string['othershort'] = 'ΑΛΛΟ';
$string['plugindist'] = 'Διανομή προσθηκών';
$string['pluginname'] = 'Use Stats';
$string['pluginname_desc'] = 'Ένα μπλοκ που μεταγλωττίζει τους χρόνους συνεδρίας';
$string['printpdf'] = 'Εκτύπωση PDF';
$string['profilefieldcontrol'] = 'Πεδίο προφίλ';
$string['showdetails'] = 'Δείξε λεπτομέρειες';
$string['sliding'] = 'Συρόμενο παράθυρο χρόνου';
$string['studentchoice'] = 'Οι μαθητές επιλέγουν';
$string['studentscansee'] = 'Οι μαθητές μπορούν να δουν';
$string['task_cache_ttl'] = 'Σύνολο Cache TTL';
$string['task_cleanup'] = 'Εκκαθάριση κενών χρόνου';
$string['task_compile'] = 'Συλλογή χρονικών κενών';
$string['timeelapsed'] = 'Χρόνος που πέρασε';
$string['to'] = '&ensp;σε&ensp;';
$string['use_stats_description'] = 'Με τη δημοσίευση αυτής της υπηρεσίας, επιτρέπετε σε απομακρυσμένους διακομιστές να ζητούν ανάγνωση στατιστικών στοιχείων τοπικών χρηστών. <br/> Όταν εγγραφείτε σε αυτήν την υπηρεσία, επιτρέπετε στον τοπικό σας διακομιστή να ζητά έναν απομακρυσμένο διακομιστή σχετικά με στατιστικά στοιχεία στα μέλη του. <br/>';
$string['use_stats_name'] = 'Απομακρυσμένη πρόσβαση στα στατιστικά'; // Is a @DYNAKEY.
$string['use_stats_rpc_service'] = 'Απομακρυσμένη πρόσβαση στα στατιστικά'; // Is a @DYNAKEY.
$string['use_stats_rpc_service_name'] = 'Απομακρυσμένη πρόσβαση στα στατιστικά'; // Is a @DYNAKEY.
$string['youspent'] = 'Ξόδεψες&ensp;';
$string['warningusestateenrolfilter'] = 'Ο έλεγχος εγγραφής είναι ενεργοποιημένος στο μπλοκ Use Stats. Αυτό μπορεί να έχει επιπτώσεις στις αναφορές εάν η δραστηριότητα του χρήστη πέσει πριν από την τελευταία ημερομηνία έναρξης εγγραφής.';

$string['plugindist_desc'] = '<p>This plugin is the community version and is published for anyone to use as is and check the plugin\'s
core application. A "pro" version of this plugin exists and is distributed under conditions to feed the life cycle, upgrade, documentation
and improvement effort.</p>
<p>Please contact one of our distributors to get "Pro" version support.</p>
<ul><li><a href="http://www.activeprolearn.com/plugin.php?plugin=block_use_stats&lang=en">ActiveProLearn SAS</a></li></ul>';
