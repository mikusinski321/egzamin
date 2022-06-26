<?php
/**
 * Order type.
 */

namespace App\Form\Type;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class OrderType.
 */
class OrderType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options Form options
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'nick',
            TextType::class,
            [
                    'label' => 'label.nick',
                    'required' => true,
                    'attr' => ['max_length' => 45],
                ]
        );
        $builder->add(
            'email',
            EmailType::class,
            [
                    'label' => 'label.email',
                    'required' => true,
                    'attr' => ['max_length' => 45],
                ]
        );
        $builder->add(
            'comment',
            TextType::class,
            [
                    'label' => 'label.comment',
                    'required' => true,
                    'attr' => ['max_length' => 45],
                ]
        );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Order::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     *
     * @psalm-return 'order'
     */
    public function getBlockPrefix(): string
    {
        return 'order';
    }
}
