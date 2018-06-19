# Adobe-Sign 10.0 webhooks-php
## Basic php 5.6 code for an Adobe Sign webhooks "reciever"

For webhooks coming from Adobe Sign to work there is a "header" parameter in the request data coming from our servers that has to be "echoed" back to our system from the web-hook "receiver".  
This is described in the [webhooks helpx article](https://helpx.adobe.com/sign/help/adobesign_api_webhooks.html) as follows:
 
> Before registering a webhook successfully, Adobe Sign verifies that the webhook URL that is provided in the registration request, really intends to receive notifications or not. For this purpose, when a new webhook registration request is received by Adobe Sign, it first makes a verification request to the webhook URL. This verification request is an HTTPS GET request sent to the webhook URL with a custom HTTP header X-AdobeSign-ClientId. The value in this header is set to the client ID of the application that is requesting to create/register the webhook. To successfully register a webhook, the webhook URL must respond to this verification request with a 2XX response code AND additionally it must send back the same client id value in one of the following two ways.

> Either in a response header X-AdobeSign-ClientId. This is the same header which was passed in the request, and be echoed back in the response.
> Or in the JSON response body with the key of xAdobeSignClientId and its value being the same client ID that was sent in the request.
 
 
This is not as difficult as it sounds as long as you have a way to capture that incoming header param on your page. Most "server-side" languages like ASP, C#, php, etc have "built-in" mechanisms for this.
 
In php (one I'm familiar with) you just need something like the following on your page:
 
```php
$asId = $_SERVER['HTTP_X_ADOBESIGN_CLIENTID'];
header('Content-Type: application/json');
header('X-AdobeSign-ClientId:'. $asId );
```
 
Those 3 lines of code capture the incoming header param called X-AdobeSign-ClientId to a variable named {$asId} and "send it back" as the page is loading as the response header to the Adobe Sign server. 
 
if you're using php, just add those 3 lines to your page code, and your webhook will register.  

You will then need to do something with all the other data coming into this webhook endpoint, but that's a whole new discussion.

In the file included here, I've added a way to have the JSON frm the incoming webhooks written to a file on the webserver so it's possible to see the incoming JSON Data structure.
