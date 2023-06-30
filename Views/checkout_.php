<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Paypal</title>
    <script src="https://kit.fontawesome.com/c26d6306d9.js" crossorigin="anonymous"></script>

</head>

<body>
    <!-- Replace "test" with your own sandbox Business account app client ID -->
    <script src="https://www.paypal.com/sdk/js?client-id=AaxEzkBqy5E7wx182P1_y0qAkTHa2RYUZf2sB6chB5i7nFfGWTFOpP0x-2ADW6H6NbGQcphi1JlNWisD&currency=MXN"></script>
    <!-- Set up a container element for the button -->
    <div id="paypal-button-container"></div>
    <script>
        paypal.Buttons({
            style: {
                color: 'blue',
                shape: 'pill',
                label: 'pay'
            },
            createOrder: function(data, actions){
                return actions.order.create({
                    purchase_units: [{
                        amount:{
                            value: 100
                        }
                    }]
                });
            },
            onApprove: function(data, actions){
                actions.order.capture().then(function(details){
                    window.location.href="completado.html";
                    
                });
            },
            onCancel: function(data){
                alert('Pago Cancelado');
                console.log(data);
            }
        }).render('#paypal-button-container');
    </script>
    
</body>

</html>
