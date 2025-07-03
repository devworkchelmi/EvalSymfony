<?php

namespace App\Form;

use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('uploadedFile', FileType::class, [
                'label' => 'Choisir un fichier (PDF, Word, Excel, Image, ZIP)',
                'mapped' => false, // très important : car ce champ n’est **pas** une propriété de l’entité
                'required' => true,
                'constraints' => [
                     new File([
                        'maxSize' => '10M', // limite à 10 Mo
                        'mimeTypes' => [
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'application/zip',
                            'application/x-rar-compressed',
                        ],
                        'mimeTypesMessage' => 'Formats autorisés : PDF, Word, Excel, Images, ZIP, RAR.',
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
