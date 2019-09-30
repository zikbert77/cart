# run
`php -S http://127.0.0.1:8000`

# task

Please, create implemnentation of Shopping Cart for our shop using vanilla PHP, that allows to:
1. Add products
2. Change quantity of added products
3. Remove product
4. Use different Cart instances (usual Cart and Wishlist)

Cart should:
- be able to output all the products, that is inside it
- has method to get total amount
- increase quantity, if user adds same product again
- has ability to use different storage type, that can be set up in config
- implement 2 storage engines - redis and sqlite

UI we do not care, it can be the simpliest, no design required.
Interaction with Cart can be done in js way (ajax) or without it (depends on your skills)

Products located in data.csv
Namespaces and class autoload required
