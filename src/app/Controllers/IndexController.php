<?php
declare(strict_types=1);

namespace app\Controllers;

use app\Models\MemberModel;
use framework\Core\Controller;
use framework\Core\Core;
use framework\Exception\AccessDeniedException;
use framework\Exception\RuntimeException;
use framework\Repsonse\Response;
use framework\Response\Response as ResponseResponse;
use framework\Service\Logger\LoggerInterface;

class IndexController extends Controller
{
  /** @var UserModel */
  protected $memberModel;

  /** @var LoggerInterface */
  protected $logger;

  /**
   * IndexController constructor
   *
   * @param MemberModel $memberModel
   * @param LoggerInterface $logger
   */
  public function __construct(MemberModel $memberModel, LoggerInterface $logger)
  {
    $this->memberModel= $memberModel;
    $this->logger = $logger;
  }

  /**
   * @return Response
   * @throws RuntimeException
   */
  public function indexAction()
  {
    $members = $this->getCache()->getCache('members');

    if (! $members) {
      $members = $this->memberModel->getAll();
      $this->getCache()->setCache('members', $members, 10);
    }

    $params = [
      'members' => $members,
      'auth'    => $this->isAuth()
    ];

    return $this->renderView("index", $params);
  }

  public function jsonAction()
  {
    $members = $this->getCache()->getCache('members');

    if (! $members) {

      $members = $this->memberModel->getAll();
      $this->getCache()->setCache('members', $members, 10);
    }

    // Presenter
    $membersJson = [];

    foreach ($members as $member) {

      $membersJson[] = [
        'id'    => $member->id,
        'role'  => $member->role,
      ];
    }

    return $this->renderView("json", ['json' => json_encode($membersJson)], 'application/json', true);
  }

  /**
   * @param $id
   * @return Response
   * @throws RuntimeException
   */
  public function memberAction($id)
  {
    $member = $this->memberModel->getById($id);

    if (! $this->isAuth()) {

      Core::redirect("/index/login");
    }

    return $this->renderView("member", ['member' => $member]);
  }

  /** 
   * @return Response
   * @throws RuntimeException
   */
  public function loginAction()
  {
    if ($this->isAuth()) {
      Core::redirect("/index/index");
    }

    if (! empty($_POST['username']) && ! empty($_POST['password'])) {

      $member = $this->memberModel->getMemberbyLoginPassword($_POST['username'], $_POST['password']);

      if ($member) {

        $this->setAuth($member->role);
        set_flash_message(t('Success Login'));

        Core::redirect("index/index");
      }

      set_flash_message(t('Invalid login data'));
      $this->logger->error("Invalid login data", ['username' => $_POST['username']]);
    }
    return $this->renderView('login');
  }

  /**
   * @return Response
   * @throws RuntimeException
   */
  public function logoutAction()
  {
    $this->destroyAuth();
    return $this->renderView("logout");
  }

  public function insertAction()
  {
    if (! $this->isAuth()) {
      throw new AccessDeniedException();
    }

    if (isset($_POST['username']) && isset($_POST['password'])) {

      $id = $this->memberModel->addMember($_POST['username'], $_POST['password']);

      $this->getCache()->dropByKey('members');

      Core::redirect("/index/index");
    }

    return $this->renderView("insert");
  }
}