# Kyoushu/InlineSwiftmailerTransport [![Build Status](https://travis-ci.org/Kyoushu/InlineSwiftmailerTransport.svg?branch=master)](https://travis-ci.org/Kyoushu/InlineSwiftmailerTransport)

A SwiftMailer transport which inlines CSS before forwarding the message to another transport for delivery

## Usage Example

    $deliveryTransport = new \Swift_SmtpTransport('localhost', 25);
    $transport = new InlineTransport($deliveryTransport, new \Swift_Events_SimpleEventDispatcher());
    
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
    
## Additional Filters

### Embed Image Filter

#### Example

Images with absolute src attributes are embedded, and the related src attributes updated with "cid:########".

    $webRootDir = '/path/to/my/web/root/dir';
    
    $transport = new InlineTransport($deliveryTransport, new \Swift_Events_SimpleEventDispatcher());
    $transport->addMessageFilter(new EmbedImageMessageFilter($webRootDir));
    
    $message = new \Swift_Message('Foo', '<html><body><img src="/images/my-image.png"</body></html>', 'text/html');
    $transport->send($message);
    
Body sent via delivery transport

    <html><body><img src="cid:09F48ag2b674"</body></html>
    
### Inline CSS Filter (Non-embedded CSS)

If you want to embed CSS added to a HTML with \<link\> elements, rather than CSS which has been embedded with \<style\> elements, add an InlineCssMessageFilter.

#### Example

    $webRootDir = '/path/to/my/web/root/dir';
    
    $transport = new InlineTransport($deliveryTransport, new \Swift_Events_SimpleEventDispatcher());
    $transport->addMessageFilter(new InlineCssMessageFilter($webRootDir));
    
    $message = new \Swift_Message('Foo', '<html><head><link rel="stylesheet" href="/css/email.css"></head><body><p>Foo</p></body></html>', 'text/html');
    $transport->send($message);
    
