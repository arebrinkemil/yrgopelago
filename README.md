![Alt text](https://media.giphy.com/media/3oKIPaGG4PDQgQDFZe/giphy.gif)

# Vinga

Vinga is a small Swedish island located at the entrance of Gothenburg's harbor. It's famous for its lighthouse, originally built in 1841, and is a popular destination for its picturesque natural scenery and its historic significance in maritime navigation.

# Vinga hotel

Vinga hotel is a finctional hotel on the island Vinga. I took inspiration from the real hotel on Paternoster which is a very similar island to Vinga.

# Instructions

This is a booking system for Yrgopelago, a fictional hotel called Vinga hotel. The system allows users to book rooms and activities for specific dates.

## Features

-   User authentication
-   Room booking
-   Activity booking
-   Price calculation
-   Admin panel for managing rooms and activities

## Installation

1. Clone the repository:

```sh
git clone https://github.com/yourusername/yrgopelago.git

2. Install PHP dependencies:
composer install

3. Install JavaScript dependencies:
npm install

```

## Code Review by [Karlsson2](https://github.com/Karlsson2)

1. In admin.php consider moving the code from line 3 to 120 into a separate file, such as adminMethods.php and require the file into admin.php with “require __DIR__ . “/adminMethods.php”;

2. In edit_activity.php consider moving the code from line 3 to 44 into a separate file, such as adminMethods.php and require the file into admin.php with “require __DIR__ . “/adminMethods.php”; 

3. In admin.php in order to enhance security, consider implementing validation of user input with a function like so:
```
function cleanInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}
```

This could then be used like so;
To prevent a disgruntled user from causing issues with your server.

```
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_activity'])) {
    $name = cleanInput($_POST['activity_name']);
    $description = cleanInput($_POST['activity_description']);
    $cost = (float) $_POST['activity_cost'];

}
```


4. In admin.php on line 14 25 116 consider using constants for your table names, if they ever need to change it is quick and easy to just change a constant instead.

```
define('TABLE_HOTEL_FEATURES', 'Hotel_Features');
```


5. In booking.php on line 17, 18, 19, 21 consider creating a sanitisation function to make the code cleaner: 

```
function sanitizeInput($input) {
    return isset($input) ? htmlspecialchars($input, ENT_QUOTES, 'UTF-8') : null;
}
```

6. In booking.php on line 39 there is some commented out code, this can sometimes be confusing, consider removing this if it is not being used. 


7. In booking.php on line 75, 82, 89, 96 consider adding the responses to an array and only encode it at the end. 

Ie replace with: 
```
$response = ['status' => 'success']
```
And have this at the end of your method: 
```
echo json_encode($response);
```

8. In bookingSuccessful.php consider taking out the script tags and further separate your code with a receipt.js file.

9. In bookingSuccessful.php on line 27 and 40 it assumes the code will always return a valid JSON string. It might be a good idea to add some error handling to ensure that parsing doesn't fail.

10. Across your project consider adding comments to help document your project for other users when they are checking it.


Overall really good code and this is just nitpicking :) 
