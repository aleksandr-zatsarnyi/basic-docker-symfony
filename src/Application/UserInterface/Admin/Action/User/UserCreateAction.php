<?php

namespace App\UserInterface\Admin\Action\User;

use App\Domain\DTO\User\UserDTO;
use App\Domain\Enum\User\UserAccessEnum;
use App\Domain\ValueObject\Email;
use App\Infrastructure\Service\Admin\UserCreateService;
use App\UserInfrastructure\API\Response\ArrayResponse;
use App\UserInterface\Admin\Type\UserCreateType;
use App\UserInterface\API\Action\AbstractAction;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Bohdan Sinchuk <bohdan.sinchuk@mirko.in.ua>
 */
class UserCreateAction extends AbstractAction
{
    public function __invoke(UserCreateService $userCreateService, Security $security)
    {
        if(!$security->isGranted(UserAccessEnum::CREATE->name, $security->getUser())) {
            throw new AccessDeniedException();
        }
       $data = $this->handleType(UserCreateType::class);
       return $this->response(new ArrayResponse(),
           $userCreateService->create(
           new UserDTO(
               new Email($data['email']),
               $data['password'],
               $data['first_name'],
               $data['last_name']
           ))
       );
    }
}