<?php
require_once __DIR__ . '/config.php';

try {
    $pdo = getPDO();

    // Create tables
    $pdo->exec("CREATE TABLE IF NOT EXISTS restaurants (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        description TEXT,
        image_url TEXT,
        tagline TEXT
    );");

    $pdo->exec("CREATE TABLE IF NOT EXISTS menu_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        restaurant_id INTEGER,
        name TEXT,
        description TEXT,
        price REAL,
        image TEXT,
        category TEXT,
        FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
    );");

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT,
        email TEXT UNIQUE,
        password_hash TEXT,
        phone TEXT,
        created_at TEXT DEFAULT CURRENT_TIMESTAMP
    );");

    $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        customer_name TEXT,
        customer_phone TEXT,
        delivery_address TEXT,
        items_json TEXT,
        total REAL,
        status TEXT DEFAULT 'pending',
        created_at TEXT DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    );");

    // Insert sample restaurants if none exist
    $count = $pdo->query("SELECT COUNT(*) FROM restaurants")->fetchColumn();
    if ($count == 0) {
        $pdo->exec("INSERT INTO restaurants (name, description, image_url, tagline) VALUES
            ('Golden Bistro', 'Classic comfort food with a modern twist', 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5', 'At Golden Bistro we celebrate familiar flavours'),
            ('La Tavola Italiana', 'Authentic Italian dishes made with love', 'https://media.istockphoto.com/id/453203585/photo/outdoor-trattoria-in-a-quiant-village-in-tuscany-italy.jpg', 'Family-run trattoria serving wood-fired pizzas'),
            ('Urban Grill House', 'Premium BBQ and grilled delicacies', 'https://www.lu-na.nl/wp-content/uploads/elementor/thumbs/Lu-Na-Woodstone-Barbecue-Over-Ons4-scaled.jpg', 'Bold, smoky flavors from our wood-fired grill'),
            ('The Carnivore', 'Savor our finest slow-baked lamb and beef', 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/2a/c1/cd/dd/welcome-to-the-carnivore.jpg', 'A meat-lovers paradise'),
            ('The Dome', 'Premium BBQ and grilled delicacies', 'https://thedome-dk.com/wp-content/uploads/2025/01/DSC03263-scaled.jpg', 'An iconic venue with stunning dome setting'),
            ('Havana Terraces', 'Vibrant Cuban flavors and Caribbean charm', 'https://www.foodhutti.com/assets/restaurant/16703302410.jpeg', 'Experience the soul of Havana'),
            ('The Smokey Cauldron', 'Authentic slow-smoked BBQ and comfort food', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS-cLe1maImYlLEc5imE14Slgv8rc4Hn1MBpQ&s', 'A smoky haven'),
            ('Tuscany Courtyard', 'Rustic Tuscan flavors & wood-fired cooking', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS-cLe1maImYlLEc5imE14Slgv8rc4Hn1MBpQ&s', 'Intimate Tuscan courtyard')");

        // Insert sample menu items
        $pdo->exec("INSERT INTO menu_items (restaurant_id, name, description, price, image) VALUES
            (1, 'Grilled Chicken Sandwich', 'Juicy grilled chicken', 750.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRAFVPpn2AEspRJR8iX_C5H_Hm63rrsoHm3BA&s'),
            (1, 'Beef Burger', 'Hand-pressed beef patty', 950.00, 'https://www.puregoldpineapples.com.au/wp-content/uploads/2020/10/aussie-beef-burger.jpg'),
            (1, 'Club Sandwich', 'Triple-layer sandwich', 700.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRS2Z50mOvhPqPe3waFatjzBhKc0v2wMxF6IA&s'),
            (2, 'Margherita Pizza', 'Classic Neapolitan pizza', 1200.00, 'https://cookingitalians.com/wp-content/uploads/2024/11/Margherita-Pizza.jpg'),
            (2, 'Pasta Alfredo', 'Creamy fettuccine', 1100.00, 'https://www.modernhoney.com/wp-content/uploads/2018/08/Fettuccine-Alfredo-Recipe-1.jpg'),
            (2, 'Lasagna', 'Layered pasta with ragù', 1350.00, 'https://www.tasteofhome.com/wp-content/uploads/2025/07/Best-Lasagna_EXPS_ATBBZ25_36333_DR_07_01_2b.jpg')");
    }

    jsonResponse(['success' => true, 'message' => 'Database initialized successfully']);

} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
}
?>