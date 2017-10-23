<?php

namespace Controllers;

class CustomerController extends BaseController
{

    const PAGE_SIZE = 5;
    protected $repository = [];
    protected $repositoryIndex = [];

    protected function init()
    {
        $this->repository = require ROOT_PATH . '/repository.php';
        $this->repositoryIndex = array_flip(
            array_column($this->repository, 'id')
        );
    }

    /**
     * Returns collection of records or certain record.
     *
     * @param  integer|null $resourceId Ressource ID to return
     *
     * @return void
     */
    public function get($resourceId = null)
    {
        $resourceId = intval($resourceId);
        if (empty($resourceId)) {
            $page = isset($_GET['page'])
                ? intval($_GET['page']) -1
                : 0;

            $offset = $page * self::PAGE_SIZE;
            if (!isset($this->repository[$offset])) {
                header('No Content', true, 204);
                exit;
            }

            $count = count($this->repository);
            $result = array_slice($this->repository, $offset, self::PAGE_SIZE);
            $first = $offset + 1 ;
            $last = $offset + self::PAGE_SIZE;
            $last = $last > $count ? $count : $last;
            header('Partial Content', true, 206);
            header("Content-range: {$first}-{$last}/{$count}");
        } else {
            if (!array_key_exists(
                $resourceId,
                $this->repositoryIndex
            )) {
                header('Not Found', true, 404);
                exit();
            }

            $key = $this->repositoryIndex[$resourceId];
            $result = $this->repository[$key];
        }

        echo json_encode($result);
    }

    /**
     * Replaces existing record with request data.
     *
     * @param  integer $resourceId Resource ID to replace.
     *
     * @return void
     */
    public function put($resourceId)
    {
        $resourceId = intval($resourceId);
        if (!array_key_exists(
            $resourceId,
            $this->repositoryIndex
        )) {
            header('Not Found', true, 404);
            exit();
        }

        $key = $this->repositoryIndex[$resourceId];
        $this->repository[$key] = $this->requestBody;

        echo json_encode($this->repository[$key]);
    }

    /**
     * Updates existing record - partial update.
     *
     * @param  integer $resourceId Resource ID to patch.
     *
     * @return void
     */
    public function patch($resourceId)
    {
        $resourceId = intval($resourceId);
        if (!array_key_exists(
            $resourceId,
            $this->repositoryIndex
        )) {
            header('Not Found', true, 404);
            exit();
        }

        $key = $this->repositoryIndex[$resourceId];
        $entity = $this->repository[$key];
        $this->repository[$key] = array_merge(
            $entity,
            $this->requestBody
        );

        echo json_encode($this->repository[$key]);
    }

    /**
     * Deletes exising resource.
     * @param  integer $resourceId Resource ID to remove.
     * @return void
     */
    public function delete($resourceId)
    {
        $resourceId = intval($resourceId);
        if (!array_key_exists(
            $resourceId,
            $this->repositoryIndex
        )) {
            header('Not Found', true, 404);
            exit();
        }

        // DELETE запрос в базу
        // удалить файл
        // отправить другой REST запрос
        $key = $this->repositoryIndex[$resourceId];
        unset($this->repository[$key]);
        echo json_encode(['status' => 'OK']);
    }
}
