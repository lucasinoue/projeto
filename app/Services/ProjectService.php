<?php 


namespace CodeProject\Services;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectValidator;
use Prettus\Validator\Exceptions\ValidatorException;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Filesystem\Factory as Storage;
/**
* 
*/
class ProjectService
{
	
	protected $repository;
	protected $validator;
	protected $filesystem;
	protected $storage;

	public function __construct(ProjectRepository $repository, ProjectValidator $validator, Filesystem $filesystem, Storage $storage)
	{
		$this->repository = $repository;
		$this->validator = $validator;
		$this->filesystem = $filesystem;
		$this->storage = $storage;
	}

	public function find($id)
	{
		try {
			return $this->repository->with(['owner', 'client'])->find($id);		
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ['error'=>true, 'Projeto não encontrado.'];
        }
	}


	public function create(array $data)
	{

		try {
			$this->validator->with($data)->passesOrFail();
		
			return $this->repository->create($data);
		} catch (ValidatorException $e) {
			return [
				'error' => true,
				'message' => $e->getMessageBag()
			];
		}
	}


	public function update(array $data, $id)
	{

		try {
			$this->validator->with($data)->passesOrFail();
			return $this->repository->update($data, $id);
		}
	 	catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ['error'=>true, 'Projeto não encontrado.'];
        }catch (ValidatorException $e) {
			return [
				'error' => true,
				'message' => $e->getMessageBag()
			];
		} 
        catch (\Exception $e) {
             return ['error'=>true, 'Ocorreu algum erro ao alterar o projeto.'];
        }  
	}

	public function delete($id) {
		try {
			$this->repository->find($id)->delete();
			return ['success'=>true, 'Projeto deletado com sucesso!'];			
		}
	 	catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ['error'=>true, 'Projeto não encontrado.'];
        } catch (\Exception $e) {
             return ['error'=>true, 'Ocorreu algum erro ao excluir o projeto.'];
        }
	}

	public function createFile(array $data)
	{

		$project = $this->repository->skipPresenter()->find($data['project_id']);
		$projectFile = $project->files()->create($data);	
	
		$this->storage->put($projectFile->id.".".$data['extension'], $this->filesystem->get($data['file']));


	}

} 