# Kyoushu/InlineSwiftmailerTransport [![Build Status](https://travis-ci.org/Kyoushu/InlineSwiftmailerTransport.svg?branch=master)](https://travis-ci.org/Kyoushu/InlineSwiftmailerTransport)

A SwiftMailer transport which inlines CSS before forwarding the message to another transport for delivery

## Filters

### Inline Embedded CSS

This filter is loaded automatically when an instance of InlineTransport is created

#### Example

    $deliveryTransport = new \Swift_SmtpTransport('localhost', 25);
    $transport = new InlineTransport($deliveryTransport, new \Swift_Events_SimpleEventDispatcher());
    $message = new \Swift_Message('Foo', '<html><head><style>p{ font-weight: bold; }</style></head><body><p>Foo</p></body></html>', 'text/html');
    $transport->send($message);
    
Body sent via delivery transport

    <html><head><style>p{ font-weight: bold; }</style></head><body><p style="font-weight: bold;">Foo</p></body></html>

### Inline Included CSS

If you want to inline CSS included with \<link\> elements, rather than CSS which has been embedded with \<style\> elements, use the InlineCssMessageFilter class.

#### Example

    $webRootDir = '/path/to/my/web/root/dir';
    
    $deliveryTransport = new \Swift_SmtpTransport('localhost', 25);
    $transport = new InlineTransport($deliveryTransport, new \Swift_Events_SimpleEventDispatcher());
    $transport->addMessageFilter(new InlineCssMessageFilter($webRootDir));
    
    $message = new \Swift_Message('Foo', '<html><head><link rel="stylesheet" href="/css/email.css"></head><body><p>Foo</p></body></html>', 'text/html');
    $transport->send($message);

### Embed Images

#### Example

\<img\> elements with absolute src attributes are embedded, and updated with "cid:########" values.

    $webRootDir = '/path/to/my/web/root/dir';
    
    $deliveryTransport = new \Swift_SmtpTransport('localhost', 25);
    $transport = new InlineTransport($deliveryTransport, new \Swift_Events_SimpleEventDispatcher());
    $transport->addMessageFilter(new EmbedImageMessageFilter($webRootDir));
    
    $message = new \Swift_Message('Foo', '<html><body><img src="/images/my-image.png"</body></html>', 'text/html');
    $transport->send($message);
    
Body sent via delivery transport

    <html><body><img src="cid:09F48ag2b674"</body></html>
    
