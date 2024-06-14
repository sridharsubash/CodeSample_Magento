1) **Billdesk PG**
Billdesk is a custom app I built for a pyment gateway Billdesk. I have listed below the functionalities addedin the app below,
    1. Display the payment method "Pay with Credit card, debit card & Net Banking" at the checkout
    2. When customer select the payment, redirecting them to Billdesk PG where they can enter the card details and complete the transaction.
    3. Once transaction is complete, Billdesk redirects the customer back to Magento store.
    4. Once the response is recieved by Billdesk, the app controller handles the response (based on the transaction status, if it is a success transaction it redirects the customer to success page and if there is error, it redirects customer to payment failiure page with the exception message / alert)
    5. The app stores the respose data from the payment gateway in the database which includes the transaction ID, transaction status, corresponding order id, customer name, customer email as a log. 

This app is done in Magento 1.X on 2015

2) **Custom Coffee **- Product Page
I have built a custom product page for a specific product called "Custom blend coffee" with a jQuery animation, where the customer can select the ingredients and specify the amout of each ingredients to get strong / mild coffee and also specify the grind type. 

These ingredients values are captured as product options and we set the options value based on the graphic notations selected by the customer and finally the give the preview / summary of what the customer have selected and adds the product to cart. 

The custom product page file is placed in below loceation,
***CustomCoffee-ProductFrontend/app/design/frontend/default/ccd/template/catalog/product/custom_view.phtml***

Also I have added the video of the frontend product page for better understading of this custom product page. 
Video URL: https://github.com/sridharsubash/CodeSample_Magento/blob/main/Custom-Coffee-Products.mp4

[![Watch the video](https://github.com/sridharsubash/CodeSample_Magento/blob/main/Custom-Coffee-Custom-Coffee-Coffee-Powders-Products.png)](https://github.com/sridharsubash/CodeSample_Magento/blob/main/Custom-Coffee-Products.mp4)