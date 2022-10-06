<?php

namespace App\Form;

use App\Entity\Cliente;
use App\Entity\Direccion;
use App\Entity\Municipios;
use App\Entity\Provincias;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DireccionType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults
        ([
            'data_class' => Direccion::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('calle', TextType::class)
            ->add('numero',TextType::class)
            ->add('puertaPisoEscalera', TextType::class)
            ->add('codPostal', IntegerType::class)
            ->add('cliente', EntityType::class, ['class'=>Cliente::class])
            ->add('municipio', EntityType::class, ['class'=>Municipios::class])
            ->add('provincia', EntityType::class, ['class'=>Provincias::class]);
    }

    public  function getBlockPrefix()
    {
        return '';
    }

    public  function getName()
    {
        return '';
    }
}