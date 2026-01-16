<?php include('fragments/head.php') ?>
<link rel="stylesheet" href="./style/presentation.css">

<div class="page-layout">
    <div class="page-title">
        <h1 class="title1">Proiect Biblioteca - DAW UNIBUC</h1>
    </div>

    <button class="green-button buton-proiect" onclick="location.href='/biblioteca/browse'">
        <i class="fa-solid fa-chevron-right"></i> Vizioneaza Proiectul
    </button>

    <div class="page-content">
        <h2 class="title2 sub-title">Cristescu Diana-Andreea - Info ID Anul 2</h2>
        
        <p class="doc-card">
            <strong class="doc-header">Prezentare Generală și Arhitectură</strong>

            Această aplicație web reprezintă o soluție digitală completă pentru modernizarea proceselor dintr-o bibliotecă publică, dezvoltată ca proiect final pentru materia <strong>Dezvoltarea Aplicațiilor Web (DAW)</strong>. Proiectul demonstrează capacitatea de a construi un sistem complex de la zero, utilizând o stivă tehnologică nativă (fără framework-uri backend majore) pentru a evidenția înțelegerea profundă a protocoalelor web și a interacțiunii cu bazele de date.
            <br><br>
            Arhitectura sistemului se bazează pe patru piloni tehnologici esențiali:
            <br><br>
            <span class="doc-list-item">
                <strong class="doc-accent">• Backend Robust (PHP & MySQL):</strong> Logica server-side este implementată în PHP pur, utilizând o structură modulară (fragmentare) pentru mentenanță. Baza de date este puternic optimizată prin utilizarea exclusivă a <strong>Procedurilor Stocate</strong> pentru manipularea datelor, asigurând performanță și securitate.
            </span>
            <span class="doc-list-item">
                <strong class="doc-accent">• Frontend Interactiv (JS & CSS):</strong> Interfața utilizatorului nu este doar statică; ea comunică asincron cu serverul prin <strong>Fetch API</strong> (AJAX), permițând actualizarea stării (împrumuturi, wishlist, pagini citite) fără reîncărcarea paginii.
            </span>
            <span class="doc-list-item last">
                <strong class="doc-accent">• Securitate "By Design":</strong> De la validarea datelor și prevenirea SQL Injection, până la criptarea parolelor și protecția sesiunilor, securitatea a fost un factor decisiv în fiecare etapă a dezvoltării.
            </span>

            Aplicația gestionează întregul ciclu de viață al interacțiunii cititor-bibliotecă: de la explorarea catalogului și rezervarea titlurilor, până la urmărirea progresului lecturii și vizualizarea termenelor de returnare într-un calendar personalizat.
        </p>

        <article>
            <p class="doc-card">
                <strong class="doc-header">1. Modulul de Explorare și Catalog (Browse)</strong>

                Pagina de "Browse" reprezintă motorul principal de descoperire al platformei. Aceasta implementează un sistem avansat de filtrare care permite utilizatorilor să navigheze prin colecții (ex. Ficțiune, Știință) și sub-categorii specifice.
                <br><br>
                În lipsa unor filtre active, pagina afișează automat <strong>"New Arrivals"</strong> (cele mai recente cărți adăugate), utilizând proceduri stocate (<code>GetLatestArrivals</code>) pentru o încărcare rapidă și optimizată. Interfața include o grilă vizuală cu coperțile cărților, titluri interactive și autori, precum și o bară de căutare dinamică animată (demonstrativă), oferind o experiență de utilizare fluidă și intuitivă.
                <br><br>
                De asemenea, sistemul include <strong>paginare automată</strong> pentru a gestiona eficient volumele mari de date, asigurând performanța indiferent de numărul de titluri din bibliotecă.
            </p>

            <p class="doc-card">
                <strong class="doc-header">2. Vizualizarea Detaliată a Cărților și Interacțiunea CRUD</strong>
                
                Această componentă esențială (<code>renderBookInfoCard</code>) transformă simpla navigare într-o experiență interactivă completă. Pe lângă afișarea metadatelor critice (titlu, autor, editură, număr pagini, stoc curent și descriere), interfața integrează funcționalități avansate bazate pe operațiuni <strong>CRUD</strong> (Create, Read, Delete), gestionate asincron prin <strong>JavaScript (Fetch API)</strong>. Această abordare asigură o experiență de utilizare fluidă, permițând actualizarea stării bazei de date fără reîncărcarea paginii.
                <br><br>
                Sistemul interoghează baza de date în timp real pentru a determina relația curentă dintre utilizator și carte, adaptând dinamic starea butoanelor de acțiune:
                <br><br>
                <span class="doc-list-item">
                    <strong class="doc-accent">• Gestiunea Listei de Favorite (Wishlist):</strong> Permite adăugarea (Create) sau eliminarea (Delete) rapidă a cărții din lista de preferințe, modificând instantaneu pictograma vizuală (stea plină/goală) în funcție de răspunsul serverului.
                </span>
                <span class="doc-list-item">
                    <strong class="doc-accent">• Sistemul de Împrumut (Borrowing):</strong> Implementează o logică de backend complexă. La apăsarea butonului "Borrow", serverul verifică întâi dacă titlul există în istoricul general al utilizatorului (<code>user_library</code>). Dacă nu, efectuează automat o inserare în biblioteca personală înainte de a crea înregistrarea în tabelul de împrumuturi active (<code>loans</code>). Interfața comută automat textul butonului între "Borrow" și "Return" bazat pe succesul operațiunii.
                </span>
                <span class="doc-list-item last">
                    <strong class="doc-accent">• Sistemul de Rezervări:</strong> Similar împrumuturilor, permite utilizatorilor să rezerve un exemplar sau să anuleze o rezervare existentă, actualizând stocul disponibil și feedback-ul vizual al butonului ("Reserve" vs "Cancel Reservation").
                </span>
                
                Pentru a asigura o continuitate vizuală perfectă, aplicația implementează și un mecanism de <strong>persistență a poziției de navigare</strong>. Folosind <code>sessionStorage</code>, poziția exactă de scroll a utilizatorului este salvată automat la accesarea unei cărți și restaurată imediat după reîncărcarea paginii. Astfel, utilizatorul nu pierde contextul vizual și poate continua explorarea listei exact de unde a rămas, eliminând frustrarea de a căuta din nou poziția anterioară în grila de cărți.
            </p>

            <p class="doc-card">
                <strong class="doc-header">3. Biblioteca Personală și Urmărirea Progresului (My Books)</strong>

                Această secțiune, accesibilă exclusiv utilizatorilor autentificați (protejată prin <code>check_login.php</code>), funcționează ca un panou de control personalizat pentru gestionarea lecturilor. Arhitectura paginii se bazează pe segregarea logică a datelor utilizatorului în trei stări distincte, fiecare populată printr-o procedură stocată dedicată MySQL, optimizată pentru a filtra rezultatele pe baza ID-ului din sesiune (<code>$_SESSION['user_id']</code>).
                <br><br>
                Interfața este organizată în secțiuni clare pentru o monitorizare eficientă a activității:
                <br><br>
                <span class="doc-list-item">
                    <strong class="doc-accent">• Just Added:</strong> Afișează titlurile recent adăugate în bibliotecă prin procedura <code>GetJustAddedBooksForUserId</code>, permițând utilizatorului să vadă rapid ce intenționează să citească.
                </span>
                <span class="doc-list-item">
                    <strong class="doc-accent">• In Progress:</strong> Este nucleul interactiv al paginii. Aici, cărțile sunt afișate împreună cu o <strong>bară de progres vizuală</strong> și un câmp de input (așa cum s-a detaliat în secțiunile anterioare), permițând actualizarea numărului de pagini citite. Datele sunt extrase prin <code>GetInProgressBooksForUserId</code>.
                </span>
                <span class="doc-list-item last">
                    <strong class="doc-accent">• Finished:</strong> Arhivează cărțile finalizate (procedura <code>GetCompletedBooksForUserId</code>), oferind un istoric vizual al realizărilor utilizatorului.
                </span>

                Componenta vizuală refolosește funcția <code>renderBookCards</code> pentru consistență stilistică și include, de asemenea, modalul de detalii (<code>renderBookInfoCard</code>), permițând utilizatorului să modifice starea cărților (ex: să returneze o carte sau să o mute în lista de favorite) direct din acest panou, fără a naviga înapoi în catalog.
            </p>

            <p class="doc-card">
                <strong class="doc-header">4. Lista de Dorințe (Wishlist)</strong>

                Această pagină servește ca un instrument de <strong>bookmarking</strong> personalizat, permițând utilizatorilor să salveze titlurile de interes pentru o consultare ulterioară. Accesul este restricționat exclusiv utilizatorilor autentificați (prin includerea <code>fragments/check_login.php</code>), asigurând confidențialitatea preferințelor de lectură.
                <br><br>
                Din punct de vedere tehnic, pagina demonstrează eficiența arhitecturii modulare a aplicației:
                <br><br>
                <span class="doc-list-item">
                    <strong class="doc-accent">• Extragerea Datelor:</strong> Se utilizează procedura stocată <code>GetUserWishlistFromId</code>, care realizează o interogare optimizată (JOIN între tabelele <code>wishlist</code> și <code>books</code>) pentru a returna rapid doar cărțile marcate de utilizatorul curent.
                </span>
                <span class="doc-list-item last">
                    <strong class="doc-accent">• Reutilizarea Componentelor UI:</strong> Afișarea vizuală nu necesită cod nou, ci apelează funcția generică <code>renderBookCards</code> (cu parametrul specific '3' pentru stilizarea cardurilor de wishlist). Aceasta lucrează în tandem cu <code>renderBookInfoCard</code>, permițând utilizatorului să deschidă detaliile unei cărți și, prin intermediul logicii CRUD explicate anterior, să o mute direct din Wishlist în Împrumuturi sau să o șteargă din listă.
                </span>

                Astfel, pagina de Wishlist acționează ca o zonă tampon între simpla explorare (Browse) și angajamentul de lectură (My Books), facilitând planificarea pe termen lung a utilizatorului.
            </p>

            <p class="doc-card">
                <strong class="doc-header">5. Sistemul Modular de Randare a Cardurilor (Render System)</strong>

                Pentru a asigura consistența vizuală și mentenanța ușoară a codului, aplicația nu rescrie codul HTML pentru afișarea cărților în fiecare pagină, ci utilizează un motor centralizat de randare (<code>fragments/render_book_card_list.php</code>). Această abordare respectă principiul programării moderne <strong>DRY (Don't Repeat Yourself)</strong>.
                <br><br>
                Funcția principală, <code>renderBookCards</code>, acționează ca un orchestrator care preia o listă de obiecte (cărți) și un parametru de stil (<code>$cardStyle</code>), adaptând ieșirea vizuală în funcție de context:
                <br><br>
                <span class="doc-list-item">
                    <strong class="doc-accent">• Stilul 1 (Complet):</strong> Utilizat în secțiunea "In Progress". Include coperta, bara de progres vizuală calculată procentual, câmpul de input pentru actualizarea paginilor și setul complet de butoane (Borrow/Return și Reserve/Cancel).
                </span>
                <span class="doc-list-item">
                    <strong class="doc-accent">• Stilul 2 (Simplificat):</strong> O variantă optimizată pentru secțiuni unde rezervarea nu este necesară, dar monitorizarea progresului rămâne activă.
                </span>
                <span class="doc-list-item">
                    <strong class="doc-accent">• Stilul 3 (Compact):</strong> Folosit în "Wishlist". Elimină bara de progres pentru a se concentra strict pe acțiunile de gestiune a colecției.
                </span>
                <br>
                Un aspect crucial al acestui modul este <strong>injecția automată a stării</strong>. Înainte de a genera HTML-ul, funcția interoghează baza de date (procedurile <code>GetBorrowedBooksFromId</code> și <code>GetReservedBooksFromId</code>) pentru a determina dacă utilizatorul are deja cartea împrumutată sau rezervată. Astfel, butoanele sunt generate direct cu starea corectă ("Return" în loc de "Borrow"), eliminând necesitatea unor verificări ulterioare din partea clientului.
            </p>

            <p class="doc-card">
                <strong class="doc-header">6. Calendarul Activităților (Schedule)</strong>

                Pagina "Schedule" oferă o perspectivă temporală asupra interacțiunii utilizatorului cu biblioteca, integrând un calendar dinamic dezvoltat complet în <strong>JavaScript (Vanilla)</strong>, fără a depinde de librării externe greoaie.
                <br><br>
                Arhitectura acestui modul implică o comunicare strânsă între client și server prin intermediul unui API dedicat JSON (<code>api/get_calendar_events.php</code>). La încărcarea paginii, un apel asincron (<code>fetchCalendarData</code>) colectează toate împrumuturile și rezervările active. Datele brute, constând în intervale de timp (Data start - Data sfârșit), sunt procesate de funcția <code>markDateRange</code>, care populează un "Map" global (<code>eventMap</code>). Acest algoritm iterează prin fiecare zi a intervalului și asociază evenimentul cu data calendaristică specifică, rezolvând totodată problemele complexe legate de fusul orar (Timezone offsets) pentru a asigura o precizie perfectă a afișării.
                <br><br>
                Renderizarea vizuală (realizată de <code>setDatesAndTitle</code>) generează dinamic grila calendaristică pentru luna curentă, marcând zilele relevante cu indicatori vizuali distincți:
                <br><br>
                <span class="doc-list-item">
                    <strong class="doc-accent">• Împrumuturi (Loans):</strong> Marcate cu pictograma specifică <i class="fa-regular fa-house" style="color: var(--accent-color-main);"></i>, indicând perioada în care cartea se află fizic la utilizator.
                </span>
                <span class="doc-list-item last">
                    <strong class="doc-accent">• Rezervări (Reservations):</strong> Marcate cu <i class="fa-regular fa-calendar-check" style="color: var(--accent-color-secondary);"></i>, evidențiind fereastra de timp în care cartea este reținută pentru ridicare.
                </span>

                Navigarea între luni este fluidă și recalculează instantaneu poziția evenimentelor fără a necesita noi interogări către server, demonstrând eficiența stocării locale a datelor în memoria browserului.
            </p>

            <p class="doc-card">
                <strong class="doc-header">7. Înregistrare și Securitatea Conturilor (Sign Up)</strong>

                Pagina de înregistrare constituie poarta de acces către funcționalitățile personalizate ale bibliotecii. Interfața este proiectată pentru a colecta datele esențiale (Nume, Prenume, Email, Parolă) într-un format intuitiv, incluzând elemente de UX moderne, precum funcționalitatea <strong>Show/Hide Password</strong> (implementată prin scriptul <code>password_show_hide.js</code>) care permite utilizatorilor să vizualizeze parola introdusă pentru a evita erorile de tastare. De asemenea, sunt integrate vizual opțiuni pentru autentificarea prin terți (Google, Apple, Facebook) pentru dezvoltări viitoare.
                <br><br>
                Logica de backend (<code>api/submit_signup.php</code>) este construită pe principii solide de securitate și validare:
                <br><br>
                <span class="doc-list-item">
                    <strong class="doc-accent">• Validarea Datelor:</strong> Înainte de orice interacțiune cu baza de date, scriptul verifică integritatea datelor trimise (câmpuri obligatorii) și confirmă că parola coincide cu câmpul de confirmare ("Confirm Password").
                </span>
                <span class="doc-list-item">
                    <strong class="doc-accent">• Unicitatea Utilizatorului:</strong> Se utilizează o funcție stocată (<code>GetUserIdFromEmail</code>) pentru a verifica rapid dacă adresa de email există deja în sistem, prevenind duplicarea conturilor.
                </span>
                <span class="doc-list-item last">
                    <strong class="doc-accent">• Criptarea Parolelor:</strong> Cel mai important aspect de securitate este stocarea parolelor. Aplicația <strong>NU</strong> stochează parole în clar. Se utilizează funcția nativă PHP <code>password_hash()</code> cu algoritmul <code>PASSWORD_DEFAULT</code> (care aplică automat "salt"-uri aleatoare), asigurând protecția datelor chiar și în cazul compromiterii bazei de date. Inserarea finală se face, ca și în restul aplicației, prin <strong>Prepared Statements</strong> pentru a bloca tentativele de SQL Injection.
                </span>
            </p>

            <p class="doc-card">
                <strong class="doc-header">8. Sistemul de Navigare Adaptiv și Autentificare (Menu & Login)</strong>

                Experiența utilizatorului (UX) este centralizată într-un **meniu lateral dinamic**, care își adaptează complet structura și conținutul în funcție de starea sesiunii utilizatorului (verificată prin `isset($_SESSION['user_id'])`). Această abordare elimină necesitatea unei pagini separate de "Login", integrând formularul direct în fluxul de navigare pentru vizitatori.
                <br><br>
                Comportamentul dual al interfeței este structurat astfel:
                <br><br>
                <span class="doc-list-item">
                    <strong class="doc-accent">• Modul Vizitator (Guest):</strong> Meniul afișează logo-ul instituției și un formular de autentificare compact, alături de opțiuni de înregistrare (Sign Up) și navigare publică (Browse/Help). Aceasta reduce fricțiunea pentru utilizatorii noi.
                </span>
                <span class="doc-list-item">
                    <strong class="doc-accent">• Modul Utilizator (Logged In):</strong> Odată autentificat, formularul dispare și este înlocuit de un "User Card" cu avatarul și numele utilizatorului. Meniul expandează automat accesul către secțiunile private (Schedule, My Books, Wishlist, Settings) și funcția de Logout.
                </span>
                <span class="doc-list-item last">
                    <strong class="doc-accent">• Securitatea Backend:</strong> Procesul de login (<code>api/submit_login.php</code>) respectă standardele stricte de securitate. Scriptul nu compară niciodată parolele în clar. Acesta extrage hash-ul parolei din baza de date folosind proceduri stocate izolate (<code>GetUserIdFromEmail</code>, <code>GetPasswordHashFromUserId</code>) și utilizează funcția criptografică <code>password_verify()</code> pentru validare, protejând conturile chiar și în cazul unui acces neautorizat la baza de date.
                </span>
            </p>

            <p class="doc-card">
                <strong class="doc-header">9. Strategia de Securitate și Integritatea Datelor</strong>

                Arhitectura aplicației a fost proiectată punând accent pe securitatea defensivă ("Defense in Depth"), implementând multiple straturi de protecție verificate direct în codul sursă pentru a contracara vulnerabilitățile web comune:
                <br><br>
                <span class="doc-list-item">
                    <strong class="doc-accent">• Protecție Avansată SQL Injection:</strong> Interacțiunea cu baza de date este dublu securizată. În primul rând, se utilizează exclusiv <strong>PDO Prepared Statements</strong>, care separă logica SQL de datele utilizatorului. În al doilea rând, utilizarea extensivă a <strong>Procedurilor Stocate</strong> (ex: <code>CALL GetUserIdFromEmail</code>) adaugă un nivel suplimentar de abstractizare, limitând accesul direct la tabele și structura bazei de date.
                </span>
                <span class="doc-list-item">
                    <strong class="doc-accent">• Managementul Securizat al Parolelor:</strong> Aplicația respectă standardele moderne de criptografie, stocând parolele exclusiv sub formă de hash-uri generate prin algoritmul <strong>Bcrypt</strong> (via <code>password_hash</code>). Verificarea la autentificare se face prin <code>password_verify</code>, asigurând că parolele în clar nu sunt niciodată expuse sau stocate, protejând utilizatorii chiar și în cazul compromiterii bazei de date.
                </span>
                <span class="doc-list-item">
                    <strong class="doc-accent">• Prevenirea XSS (Cross-Site Scripting):</strong> Toate datele afișate în interfață (nume autori, titluri cărți, descrieri) sunt trecute sistematic prin funcția de sanitizare <code>htmlspecialchars()</code>. Aceasta neutralizează orice tentativă de injectare a codului malițios JavaScript prin input-urile utilizatorilor.
                </span>
                <span class="doc-list-item">
                    <strong class="doc-accent">• Validare Server-Side și Anti-Spoofing:</strong> Aplicația nu se bazează pe validarea din browser (care poate fi ușor ocolită). Toate datele critice sunt re-verificate pe server (ex: verificarea existenței email-ului, coincidența parolelor). Mai mult, acțiunile sensibile (împrumuturi, modificări profil) folosesc identificatorul din sesiune (<code>$_SESSION['user_id']</code>) și ignoră orice ID trimis prin formulare ascunse, eliminând riscul de <code>Form Spoofing</code>.
                </span>
                <span class="doc-list-item last">
                    <strong class="doc-accent">• Controlul Accesului:</strong> Resursele private sunt protejate prin mecanisme de includere modulară (ex: <code>fragments/check_login.php</code>), care blochează execuția scriptului pentru utilizatorii neautentificați înainte de a încărca orice conținut sensibil.
                </span>
            </p>
        </article>
    </div>
</div>

<?php include('fragments/foot.php') ?>