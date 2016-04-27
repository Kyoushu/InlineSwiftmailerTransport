# Kyoushu/InlineSwiftmailerTransport

A SwiftMailer transport which inlines CSS before forwarding the message to another transport for delivery

## Usage Example

    $deliveryTransport = new \Swift_SmtpTransport('localhost', 25);
    $transport = new \Kyoushu\InlineSwiftmailerTransport\InlineTransport($deliveryTransport, new \Swift_Events_SimpleEventDispatcher());
    
    $message = new Swift_Message(
        'Foo',
        '<html>
            <head>
                <style>
                    body{
                        font-family: sans-serif;
                    }
                    
                    p{
                        display: block;
                        text-align: center;
                    }
                </style>
            </head>
            <body>
                <p>Bar</p>
            </body>
        </html>',
        'text/html'
    );
    
    $transport->send($message);
    
With the example above, the following body would be sent using $deliveryTransport

    <html>
        <head>
            <style>
                body{
                    font-family: sans-serif;
                }
                
                p{
                    display: block;
                    text-align: center;
                }
            </style>
        </head>
        <body style="font-family: sans-serif;">
            <p style="display: block; text-align: center;">Bar</p>
        </body>
    </html>