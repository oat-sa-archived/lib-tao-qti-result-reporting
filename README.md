# Library for the reading of the compiled deliveries and results of the delivery executions

### Installation

Add in the `composer.json`:
 
```
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/oat-sa/lib-tao-qti-result-reporting.git"
    }
],
"require": {
    ...
    "oat-sa/lib-tao-qti-result-reporting": "dev-develop"
},
```

### Example

#### Reading of the compiled deliveries
```
$delivery = \core_kernel_classes_Resource($deliveryUri);
$deliveryReader = new DeliveryReader($delivery);

/** @var TestReader */
$deliveryReader->getTestReader()

/** @var TestPartReader */
$deliveryReader->getTestPartReaders();

/** @var ItemReader $itemReader */
foreach ($testPartReader->getItemReaders() as $href => $itemReader) {
    /* @var \stdClass with Qti*/
    $itemReader->getQtiItem();
    
    /* @var \stdClass with Data from variable_elements.json*/
    $itemReader->getQtiVariableElements()
}
```

#### Parsing of the Qti

```
/** @var ItemReader $itemReader */
$qti = $itemReader->getQtiItem();
$qtiItemParser = new QtiItemParser($qti);


/**
 * Response identifier can be changed from GUI
 * Collect all of the possible identifiers
 */
$qtiItemParser->getResponseIdentifiers();
```