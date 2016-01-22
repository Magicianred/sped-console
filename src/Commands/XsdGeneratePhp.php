<?php

namespace NFePHP\Commands;

use Goetas\XML\XSDReader\SchemaReader;
use NFePHP\Commands\InputArgs\XsdGeneratePhp as XsdGeneratePhpArgs;
use NFePHP\Commands\Processors\XsdGeneratePhp as XsdGeneratePhpProcessor;
use NFePHP\XsdConverter\PhpConverter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class XsdGeneratePhp extends Command
{
    const XSD_NFE_NAMESPACE = 'http://www.portalfiscal.inf.br/nfe';
    const XSD_SIGNATURE_NAMESPACE = 'http://www.w3.org/2000/09/xmldsig#';

    protected function configure()
    {
        $this->setName('xsd:generate:php');
        $this->setDescription('Generate PHP Classes from XSD files');
        $this->setDefinition(XsdGeneratePhpArgs::getDefinitions());
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputArgs = new XsdGeneratePhpArgs($input);

        $converter = new PhpConverter($inputArgs->getNamingStrategy());

        $processor = $this->createPhpProcessor(
            $inputArgs,
            $output,
            $converter,
            new SchemaReader(),
            array(self::XSD_NFE_NAMESPACE, self::XSD_SIGNATURE_NAMESPACE)
        );
        $processor->execute($this);
        return 0;
    }

    /**
     * @param XsdGeneratePhpArgs $input
     * @param OutputInterface $output
     * @param PhpConverter $converter
     * @param SchemaReader $schemaReader
     * @param array $targetNamespaces
     * @return XsdGeneratePhpProcessor
     */
    public function createPhpProcessor(
        XsdGeneratePhpArgs $input,
        OutputInterface $output,
        PhpConverter $converter,
        SchemaReader $schemaReader,
        array $targetNamespaces
    ) {
        return new XsdGeneratePhpProcessor($input, $output, $converter, $schemaReader, $targetNamespaces);
    }
}