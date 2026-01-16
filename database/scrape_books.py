import requests
from bs4 import BeautifulSoup
import time
import random
from datetime import datetime

# --- 1. CONFIGURATION ---

FILENAME = "books_data_dump.sql"
BOOKS_PER_LINK = 20  # How many books to scrape per category link

# MAPPING: Category Name -> (Category_ID, Collection_ID)
# 1 = Fiction, 2 = Non-Fiction, 3 = YA & Children
CATEGORY_DB_MAP = {
    "Fantasy & Magic": (1, 1),
    "Science Fiction": (2, 1),
    "Mystery & Thriller": (3, 1),
    "Romance": (4, 1),
    "Horror": (5, 1),
    "Historical Fiction": (6, 1),
    "Biography & Memoir": (7, 2),
    "Self-help & Psychology": (8, 2),
    "Business & Finance": (9, 2),
    "History & Politics": (10, 2),
    "Science & Nature": (11, 2),
    "Arts & Crafts": (12, 2),
    "Teenage Fiction & True Stories": (13, 3),
    "Coming of Age": (14, 3),
    "Children's Picture Books": (15, 3),
    "Middle Grade Adventures": (16, 3),
    "Educational Material": (16, 3)
}

LANGUAGE_MAP = {"English": 1, "German": 2, "Spanish": 3, "French": 4, "Italian": 5}

# --- SCRAPE TARGETS ---
SCRAPE_CONFIG = {
    "https://www.libristo.ro/en/books-in-english/fantasy": ("Fantasy & Magic", "English"),
    "https://www.libristo.ro/en/books-in-english/science-fiction": ("Science Fiction", "English"),
    "https://www.libristo.ro/en/books-in-english/thriller-suspense-fiction": ("Mystery & Thriller", "English"),
    "https://www.libristo.ro/en/books-in-english/romance": ("Romance", "English"),
    "https://www.libristo.ro/en/books-in-english/horror-supernatural-fiction": ("Horror", "English"),
    "https://www.libristo.ro/en/books-in-english/historical-fiction": ("Historical Fiction", "English"),
    "https://www.libristo.ro/en/books-in-english/biography-non-fiction-prose": ("Biography & Memoir", "English"),
    "https://www.libristo.ro/en/books-in-english/health-relationships-personal-development": ("Self-help & Psychology", "English"),
    "https://www.libristo.ro/en/books-in-english/economics-finance-business-management": ("Business & Finance", "English"),
    "https://www.libristo.ro/en/books-in-english/history": ("History & Politics", "English"),
    "https://www.libristo.ro/en/books-in-english/earth-sciences-geography-environment-planning": ("Science & Nature", "English"),
    "https://www.libristo.ro/en/books-in-english/handicrafts-decorative-arts-crafts": ("Arts & Crafts", "English"),
    "https://www.libristo.ro/en/books-in-english/children-s-teenage-fiction-true-stories": ("Teenage Fiction & True Stories", "English"),
    "https://www.libristo.ro/en/books-in-english/children-s-teenage-personal-social-issues": ("Coming of Age", "English"),
    "https://www.libristo.ro/en/books-in-english/books-for-very-young-children-children-s-picture-books-activity-books": ("Children's Picture Books", "English"),
    "https://www.libristo.ro/en/books-in-english/educational-material": ("Educational Material", "English"),

    "https://www.libristo.ro/en/books-in-german/fantasy": ("Fantasy & Magic", "German"),
    "https://www.libristo.ro/en/books-in-german/science-fiction": ("Science Fiction", "German"),
    "https://www.libristo.ro/en/books-in-german/thriller-suspense-fiction": ("Mystery & Thriller", "German"),
    "https://www.libristo.ro/en/books-in-german/romance": ("Romance", "German"),
    "https://www.libristo.ro/en/books-in-german/horror-supernatural-fiction": ("Horror", "German"),
    "https://www.libristo.ro/en/books-in-german/historical-fiction": ("Historical Fiction", "German"),
    "https://www.libristo.ro/en/books-in-german/biography-non-fiction-prose": ("Biography & Memoir", "German"),
    "https://www.libristo.ro/en/books-in-german/health-relationships-personal-development": ("Self-help & Psychology", "German"),
    "https://www.libristo.ro/en/books-in-german/economics-finance-business-management": ("Business & Finance", "German"),
    "https://www.libristo.ro/en/books-in-german/history": ("History & Politics", "German"),
    "https://www.libristo.ro/en/books-in-german/earth-sciences-geography-environment-planning": ("Science & Nature", "German"),
    "https://www.libristo.ro/en/books-in-german/handicrafts-decorative-arts-crafts": ("Arts & Crafts", "German"),
    "https://www.libristo.ro/en/books-in-german/children-s-teenage-fiction-true-stories": ("Teenage Fiction & True Stories", "German"),
    "https://www.libristo.ro/en/books-in-german/children-s-teenage-personal-social-issues": ("Coming of Age", "German"),
    "https://www.libristo.ro/en/books-in-german/books-for-very-young-children-children-s-picture-books-activity-books": ("Children's Picture Books", "German"),
    "https://www.libristo.ro/en/books-in-german/educational-material": ("Educational Material", "German"),

    "https://www.libristo.ro/en/books-in-spanish/fantasy": ("Fantasy & Magic", "Spanish"),
    "https://www.libristo.ro/en/books-in-spanish/science-fiction": ("Science Fiction", "Spanish"),
    "https://www.libristo.ro/en/books-in-spanish/thriller-suspense-fiction": ("Mystery & Thriller", "Spanish"),
    "https://www.libristo.ro/en/books-in-spanish/romance": ("Romance", "Spanish"),
    "https://www.libristo.ro/en/books-in-spanish/horror-supernatural-fiction": ("Horror", "Spanish"),
    "https://www.libristo.ro/en/books-in-spanish/historical-fiction": ("Historical Fiction", "Spanish"),
    "https://www.libristo.ro/en/books-in-spanish/biography-non-fiction-prose": ("Biography & Memoir", "Spanish"),
    "https://www.libristo.ro/en/books-in-spanish/health-relationships-personal-development": ("Self-help & Psychology", "Spanish"),
    "https://www.libristo.ro/en/books-in-spanish/economics-finance-business-management": ("Business & Finance", "Spanish"),
    "https://www.libristo.ro/en/books-in-spanish/history": ("History & Politics", "Spanish"),
    "https://www.libristo.ro/en/books-in-spanish/earth-sciences-geography-environment-planning": ("Science & Nature", "Spanish"),
    "https://www.libristo.ro/en/books-in-spanish/handicrafts-decorative-arts-crafts": ("Arts & Crafts", "Spanish"),
    "https://www.libristo.ro/en/books-in-spanish/children-s-teenage-fiction-true-stories": ("Teenage Fiction & True Stories", "Spanish"),
    "https://www.libristo.ro/en/books-in-spanish/children-s-teenage-personal-social-issues": ("Coming of Age", "Spanish"),
    "https://www.libristo.ro/en/books-in-spanish/books-for-very-young-children-children-s-picture-books-activity-books": ("Children's Picture Books", "Spanish"),
    "https://www.libristo.ro/en/books-in-spanish/educational-material": ("Educational Material", "Spanish"),

    "https://www.libristo.ro/en/books-in-french/fantasy": ("Fantasy & Magic", "French"),
    "https://www.libristo.ro/en/books-in-french/science-fiction": ("Science Fiction", "French"),
    "https://www.libristo.ro/en/books-in-french/thriller-suspense-fiction": ("Mystery & Thriller", "French"),
    "https://www.libristo.ro/en/books-in-french/romance": ("Romance", "French"),
    "https://www.libristo.ro/en/books-in-french/horror-supernatural-fiction": ("Horror", "French"),
    "https://www.libristo.ro/en/books-in-french/historical-fiction": ("Historical Fiction", "French"),
    "https://www.libristo.ro/en/books-in-french/biography-non-fiction-prose": ("Biography & Memoir", "French"),
    "https://www.libristo.ro/en/books-in-french/health-relationships-personal-development": ("Self-help & Psychology", "French"),
    "https://www.libristo.ro/en/books-in-french/economics-finance-business-management": ("Business & Finance", "French"),
    "https://www.libristo.ro/en/books-in-french/history": ("History & Politics", "French"),
    "https://www.libristo.ro/en/books-in-french/earth-sciences-geography-environment-planning": ("Science & Nature", "French"),
    "https://www.libristo.ro/en/books-in-french/handicrafts-decorative-arts-crafts": ("Arts & Crafts", "French"),
    "https://www.libristo.ro/en/books-in-french/children-s-teenage-fiction-true-stories": ("Teenage Fiction & True Stories", "French"),
    "https://www.libristo.ro/en/books-in-french/children-s-teenage-personal-social-issues": ("Coming of Age", "French"),
    "https://www.libristo.ro/en/books-in-french/books-for-very-young-children-children-s-picture-books-activity-books": ("Children's Picture Books", "French"),
    "https://www.libristo.ro/en/books-in-french/educational-material": ("Educational Material", "French"),

    "https://www.libristo.ro/en/books-in-italian/fantasy": ("Fantasy & Magic", "Italian"),
    "https://www.libristo.ro/en/books-in-italian/science-fiction": ("Science Fiction", "Italian"),
    "https://www.libristo.ro/en/books-in-italian/thriller-suspense-fiction": ("Mystery & Thriller", "Italian"),
    "https://www.libristo.ro/en/books-in-italian/romance": ("Romance", "Italian"),
    "https://www.libristo.ro/en/books-in-italian/horror-supernatural-fiction": ("Horror", "Italian"),
    "https://www.libristo.ro/en/books-in-italian/historical-fiction": ("Historical Fiction", "Italian"),
    "https://www.libristo.ro/en/books-in-italian/biography-non-fiction-prose": ("Biography & Memoir", "Italian"),
    "https://www.libristo.ro/en/books-in-italian/health-relationships-personal-development": ("Self-help & Psychology", "Italian"),
    "https://www.libristo.ro/en/books-in-italian/economics-finance-business-management": ("Business & Finance", "Italian"),
    "https://www.libristo.ro/en/books-in-italian/history": ("History & Politics", "Italian"),
    "https://www.libristo.ro/en/books-in-italian/earth-sciences-geography-environment-planning": ("Science & Nature", "Italian"),
    "https://www.libristo.ro/en/books-in-italian/handicrafts-decorative-arts-crafts": ("Arts & Crafts", "Italian"),
    "https://www.libristo.ro/en/books-in-italian/children-s-teenage-fiction-true-stories": ("Teenage Fiction & True Stories", "Italian"),
    "https://www.libristo.ro/en/books-in-italian/children-s-teenage-personal-social-issues": ("Coming of Age", "Italian"),
    "https://www.libristo.ro/en/books-in-italian/books-for-very-young-children-children-s-picture-books-activity-books": ("Children's Picture Books", "Italian"),
    "https://www.libristo.ro/en/books-in-italian/educational-material": ("Educational Material", "Italian")
}

# --- 2. STORAGE & HELPERS ---
data_store = {"authors": {}, "publishers": {}, "books": []}
counters = {"auth": 1, "pub": 1, "book": 1}

def escape_sql(text):
    if not text: return ""
    return text.replace("\\", "\\\\").replace("'", "\\'")

def get_new_id(type_key, name):
    name = name.strip()
    if not name: name = "Unknown"
    
    if name in data_store[type_key]: 
        return data_store[type_key][name]
    
    new_id = counters["auth" if type_key == "authors" else "pub"]
    data_store[type_key][name] = new_id
    
    if type_key == "authors": counters["auth"] += 1
    else: counters["pub"] += 1
    return new_id

def get_soup(url):
    headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Language': 'en-US,en;q=0.5'
    }
    try:
        r = requests.get(url, headers=headers, timeout=20)
        return BeautifulSoup(r.text, 'html.parser') if r.status_code == 200 else None
    except Exception as e:
        print(f"  [!] Connection Error: {e}")
        return None

def parse_date(date_str):
    if not date_str: return "2023-01-01"
    date_str = date_str.replace(",", "").strip()
    try:
        return datetime.strptime(date_str, "%B %Y").strftime("%Y-%m-%d")
    except:
        try:
            return datetime.strptime(date_str, "%d. %m. %Y").strftime("%Y-%m-%d")
        except:
            try:
                if len(date_str) == 4 and date_str.isdigit(): return f"{date_str}-01-01"
            except: pass
    return "2023-01-01"

# --- 3. SCRAPING FUNCTIONS ---

def find_book_links(soup):
    links = []
    # 1. Target grid
    container = soup.select_one("#products-here")
    search_area = container if container else soup
    
    # 2. Find any link with /book/ in URL
    anchors = search_area.select("a[href*='/book/']")
    for a in anchors:
        href = a.get('href')
        if href:
            if not href.startswith("http"):
                href = "https://www.libristo.ro" + href
            links.append(href)
    return list(dict.fromkeys(links))

def scrape_book_details(url):
    soup = get_soup(url)
    if not soup: return None
    
    info = {
        'title': "Unknown Title", 
        'img': "", 
        'desc': "", 
        'pub': "Unknown", 
        'date': "2050-01-01", 
        'pages': 0,
        'authors': []
    }
    
    # 1. Title
    try: info['title'] = soup.find('h1', class_='font-heading').get_text(strip=True)
    except: pass
    
    # 2. Image
    try: 
        img_tag = soup.select_one("img.shadow-jacket")
        if img_tag: info['img'] = img_tag['src']
    except: pass
    
    # 3. Authors
    try:
        author_links = soup.select("a[href*='/author/']")
        for a in author_links:
            name = a.get_text(strip=True)
            if name and name not in info['authors']: info['authors'].append(name)
    except: pass
    if not info['authors']: info['authors'].append("Unknown Author")

    # 4. Publisher & Date
    try:
        pub_tag = soup.select_one("a[href*='/publisher/']")
        if pub_tag:
            info['pub'] = pub_tag.get_text(strip=True)
            date_span = pub_tag.find_next_sibling("span")
            if date_span:
                info['date'] = parse_date(date_span.get_text(strip=True))
    except: pass

    # 5. Description
    try:
        synopsis = soup.select_one("#synopsis-text .italic")
        if synopsis:
            text = synopsis.get_text(separator=" ", strip=True)
            info['desc'] = (text[:1000] + "...") if len(text) > 1000 else text
    except: pass

    # 6. Page Count
    # Target structure: <span ...>Number of pages</span> <span ...><strong ...>160</strong></span>
    try:
        # Find the text node "Number of pages"
        label_text = soup.find(string=re.compile("Number of pages"))
        if label_text:
            # Get the parent <span> of the text
            label_span = label_text.parent 
            # Get the next sibling tag (ignoring whitespace/newlines)
            value_span = label_span.find_next_sibling()
            
            if value_span:
                raw_val = value_span.get_text(strip=True)
                if raw_val.isdigit():
                    info['pages'] = int(raw_val)
    except Exception as e:
        # print(f"Page count error: {e}") 
        pass

    # Fallback only if scraping failed completely
    if info['pages'] == 0:
        info['pages'] = random.randint(200, 600)

    return info

# --- 4. MAIN LOOP ---

print("--- STARTING SCRAPER ---")

for link_url, (cat_name, lang_name) in SCRAPE_CONFIG.items():
    print(f"\n> Category: {cat_name} ({lang_name})")
    
    if cat_name not in CATEGORY_DB_MAP:
        print(f"  [!] Skipped: '{cat_name}' not in DB map.")
        continue
    
    cat_id, coll_id = CATEGORY_DB_MAP[cat_name]
    lang_id = LANGUAGE_MAP.get(lang_name, 1)

    soup = get_soup(link_url)
    if not soup: continue

    links = find_book_links(soup)
    print(f"  Found {len(links)} books.")

    count = 0
    for book_url in links:
        if count >= BOOKS_PER_LINK: break
        
        print(f"    - Scraping: {book_url}...", end="", flush=True)
        details = scrape_book_details(book_url)
        
        if not details: 
            print(" Failed.")
            continue
            
        print(" OK.")
        pub_id = get_new_id("publishers", details['pub'])
        
        book_obj = {
            "id": counters['book'],
            "title": details['title'],
            "img": details['img'],
            "desc": details['desc'],
            "pages": details['pages'],
            "pub_id": pub_id,
            "date": details['date'],
            "lang_id": lang_id,
            "coll_id": coll_id,
            "cat_id": cat_id,
            "author_ids": [get_new_id("authors", a) for a in details['authors']]
        }
        data_store['books'].append(book_obj)
        counters['book'] += 1
        count += 1
        time.sleep(1.0)

# --- 5. GENERATE SQL (MATCHING YOUR TABLE STRUCTURE) ---

print(f"\n> Writing to {FILENAME}...")

def write_batches(file, table_name, columns, values_list, batch_size=100):
    if not values_list: return
    total = len(values_list)
    for i in range(0, total, batch_size):
        batch = values_list[i : i + batch_size]
        file.write(f"INSERT INTO `{table_name}` ({columns}) VALUES\n")
        file.write(",\n".join(batch) + ";\n\n")

with open(FILENAME, "w", encoding="utf-8") as f:
    f.write(f"-- GENERATED SQL DUMP: {datetime.now()}\n")
    f.write("USE `biblioteca_database`;\n\n")

    # 1. PUBLISHERS
    f.write("-- TABLE: PUBLISHERS\n")
    pub_data = [f"({pid}, '{escape_sql(n)}')" for n, pid in data_store['publishers'].items()]
    write_batches(f, "publishers", "`id`, `name`", pub_data)

    # 2. AUTHORS
    f.write("-- TABLE: AUTHORS\n")
    auth_data = [f"({aid}, '{escape_sql(n)}')" for n, aid in data_store['authors'].items()]
    write_batches(f, "authors", "`id`, `name`", auth_data)

    # 3. BOOKS
    f.write("-- TABLE: BOOKS\n")
    book_lines = []
    for b in data_store['books']:
        stock = random.randint(1, 15)
        reserved = random.randint(0, stock)
        reserved = random.randint(0, reserved)
        reserved = random.randint(0, reserved)
        # Structure: id, title, date, stock, reserved, page_count, publisher, language, collection, image, goodreads, description
        val = f"({b['id']}, '{escape_sql(b['title'])}', '{b['date']}', {stock}, {reserved}, {b['pages']}, {b['pub_id']}, {b['lang_id']}, {b['coll_id']}, '{escape_sql(b['img'])}', NULL, '{escape_sql(b['desc'])}')"
        book_lines.append(val)
    
    write_batches(f, "books", "`id`, `title`, `publish_date`, `stock_count`, `reserved_count`, `page_count`, `publisher_id`, `language_id`, `collection_id`, `image_path`, `goodreads_link`, `description`", book_lines, 50)

    # 4. JUNCTIONS
    f.write("-- TABLE: BOOK_CATEGORIES\n")
    cat_lines = [f"({b['id']}, {b['cat_id']})" for b in data_store['books']]
    write_batches(f, "book_categories", "`book_id`, `category_id`", cat_lines, 200)

    f.write("-- TABLE: BOOK_AUTHORS\n")
    auth_lines = []
    for b in data_store['books']:
        for aid in b['author_ids']:
            auth_lines.append(f"({b['id']}, {aid})")
    write_batches(f, "book_authors", "`book_id`, `author_id`", auth_lines, 200)

print("Done! Check 'library_data_final.sql'.")