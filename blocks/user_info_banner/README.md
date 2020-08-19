# usersessions
Οι ρυθμίσεις των χρόνων μαθημάτων User Sessions Block αποθηκεύεται στον πίνακα “ user_sessions_settings”. 
Το block User Sessions συνδέεται με το Training Sessions Report.
Υπολογίζει την συνολική διάρκεια του μαθήματος για κάθε χρήστη και αν η ελάχιστη διάρκεια είναι μικρότερη από την συνολική διάρκεια του χρήστη τότε παράγει χρόνους προβολής για κάθε δραστηριότητα στον πίνακα “ logstore_standard_log” ξεκινώντας από την τελευταία πρόσβαση του χρήστη αν υπάρχει αλλιώς από την ημερομηνία εγγραφής του στο μάθημα.
Μετά την εκχώρηση χρόνου στην τελευταία δραστηριότητα εκχωρείται logout στον πίνακα “ logstore_standard_log” ώστε το  σταματήσει η λειτουργία του Trainings Sessions Report να αυξάνει χρόνους και τέλος εκχώρηση “ user_lastaccess ” 10 δευτερόλεπτα μετά από τον τελευταίο χρόνο σε δραστηριότητα (αν έχει ξανασυνδεθεί γίνεται επεξεργασία της εγγραφής αλλιώς δημιουργείται νέα) .
