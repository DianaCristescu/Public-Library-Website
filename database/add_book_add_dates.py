import random
from datetime import datetime, timedelta

# --- CONFIGURATION ---
OUTPUT_FILE = "update_dates.sql"
YEARS_BACK = 3

# Define the date range (Now back to 3 years ago)
end_date = datetime.now()
start_date = end_date - timedelta(days=365 * YEARS_BACK)

def get_random_date(start, end):
    """Generates a random date between start and end"""
    delta = end - start
    random_days = random.randrange(delta.days)
    # Note: We don't need seconds for DATE type
    return start + timedelta(days=random_days)

def generate_sql():
    print("--- DATE ADDED GENERATOR (DATE TYPE) ---")
    
    # 1. Get User Input
    try:
        start_id = int(input("Enter Start Book ID: "))
        end_id = int(input("Enter End Book ID: "))
    except ValueError:
        print("Error: Please enter valid numbers.")
        return

    if start_id > end_id:
        print("Error: Start ID cannot be larger than End ID.")
        return

    print(f"\nGenerating dates for IDs {start_id} to {end_id}...")

    # 2. Open File and Write SQL
    with open(OUTPUT_FILE, "w", encoding="utf-8") as f:
        f.write(f"-- Generated Date Updates: {datetime.now()}\n")
        f.write("USE `biblioteca_database`;\n\n")
        
        f.write("-- Update Statements\n")
        
        # 3. Generate Random Dates
        f.write("START TRANSACTION;\n")
        
        count = 0
        for book_id in range(start_id, end_id + 1):
            random_dt = get_random_date(start_date, end_date)
            # CHANGED: Format is just YYYY-MM-DD
            formatted_date = random_dt.strftime('%Y-%m-%d')
            
            # Write the update line
            # UPDATE books SET date_added = '2024-01-05' WHERE id = 5;
            f.write(f"UPDATE `books` SET `date_added` = '{formatted_date}' WHERE `id` = {book_id};\n")
            
            count += 1
            if count % 1000 == 0:
                print(f"  Generated {count} dates...")

        f.write("COMMIT;\n")

    print(f"\nDone! Generated {count} updates.")
    print(f"File saved as: {OUTPUT_FILE}")
    print("Run this file in MySQL Workbench or phpMyAdmin to apply changes.")

if __name__ == "__main__":
    generate_sql()