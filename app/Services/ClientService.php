<?php 


namespace CodeProject\Services;

use CodeProject\Repositories\ClientRepository;
use CodeProject\Validators\ClientValidator;
use Prettus\Validator\Exceptions\ValidatorException;


/**
* 
*/
class ClientService
{
	
	protected $repository;
	protected $validator;

	public function __construct(ClientRepository $repository, CLientValidator $validator)
	{
		$this->repository = $repository;
		$this->validator = $validator;
	}


	public function find($id)
	{
		try {
			return $this->repository->find($id);		
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ['error'=>true, 'Cliente não encontrado.'];
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
            return ['error'=>true, 'Cliente não encontrado.'];
        }catch (ValidatorException $e) {
			return [
				'error' => true,
				'message' => $e->getMessageBag()
			];
		}
         catch (\Exception $e) {
             return ['error'=>true, 'Ocorreu algum erro ao alterar o cliente.'];
        }  
	}

	public function delete($id) {
		try {
			$this->repository->find($id)->delete();
			return ['success'=>true, 'Cliente deletado com sucesso!'];			
		}catch (\Illuminate\Database\QueryException $e) {
			return ['error'=>true, 'Cliente não pode ser apagado pois existe um ou mais projetos vinculados a ele.'];
		}
	 	catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ['error'=>true, 'Cliente não encontrado.'];
        } catch (\Exception $e) {
             return ['error'=>true, 'Ocorreu algum erro ao excluir o cliente.'];
        }
	}

} 