<?php

namespace App\Form;

use App\Entity\Categoria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoriaType extends AbstractType
{
    //esto tenemos que pasarlo al CategoriaControler para que el controller  a traves de este fpormulario compruebe que los datos son correctos
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categoria', TextType::class)//recordar que al hacerlo con el comando hace falta ponerlo con el text type
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categoria::class,
        ]);
    }

    //el por defecto le pone un nombre al formulario

    public  function getBlockPrefix()
    {
        return '';
    }

    public  function getName()
    {
        return '';
    }
}
