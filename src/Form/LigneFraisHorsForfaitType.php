<?php
/**
 * GSB Frais Symfony 4.3 - 2019
 * @author Alexandre Lebailly <alexlebaillypro@gmail.com> <http://iamalex.fr>
 */

namespace App\Form;

use App\Entity\LigneFraisHorsForfait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneFraisHorsForfaitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Libellé'
            ])
            ->add('dateCreation', DateType::class, [
                'attr' => ['class' => 'form-control'],
                'widget' => 'single_text',
                'label' => 'Date'
            ])
            ->add('montant', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-outline-info btn-block'],
                'label' => 'Valider'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LigneFraisHorsForfait::class,
        ]);
    }
}
