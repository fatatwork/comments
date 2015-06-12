<?php
class vk {
	private $token;
	private $app_id;
	private $group_id;
	private $delta;
	public function __construct( $token, $delta = 5, $app_id = 'xxxxxxx', $group_id = 'xxxxxxxx' ) {
		$this->token = $token;
		$this->delta = $delta;
		$this->app_id = $app_id;
		$this->group_id = -$group_id;
	}
	public function post( $desc, $photo, $link ) {
		if( rand( 0, 99 ) < $this->delta ) {
			$data = json_decode(
				$this->execute(
					'wall.post',
					array(
						'owner_id' => $this->group_id,
						'from_group' => 1,
						'message' => $desc,
						'attachments' => 'photo-' . $this->group_id . '_' . $photo . ',' . $link
					)
				)
			);
			if( isset( $data->error ) ) {
				return $this->error( $data );
			}
			return $data->response->post_id;
		}
		return 0;
	}
	public function getOneUser($user_id){
		$data = json_decode(
			$this->execute(
				'users.get',
				array(
					'user_ids' => $user_id,
					'fields'=>"photo_50",
					'name_case'=>'nom'
				)
			)
		);
		if( isset( $data->error ) ) {
			return $this->error( $data );
		}
		return $data->response;
	}
	public function getComments($post_id, $count, $sort){
		$data = json_decode(
			$this->execute(
				'wall.getComments',
				array(
					'owner_id' => $this->group_id,
					'post_id'=>$post_id,
					'need_likes'=>0,
					'count'=>$count,
					'sort'=>$sort,//asc — от старых к новым, desc - от новых к старым)
					'preview_length'=>0
				)
			)
		);
		if( isset( $data->error ) ) {
			return $this->error( $data );
		}
		return $data->response;
	}
	public function getRepost($object_str, $group_id){
		$data = json_decode(
			$this->execute(
				'wall.repost',
				array(
					'object' => $object_str,
					'group_id'=>$group_id
				)
			)
		);
		if( isset( $data->error ) ) {
			return $this->error( $data );
		}
		return $data->response;
	}
	public function addComment( $desc, $post_id, $sticker) {
			$data = json_decode(
				$this->execute(
					'wall.addComment',
					array(
						'owner_id' => $this->group_id,
						'from_group' => 0,
						'text' => $desc,
						'post_id'=>$post_id,
						'sticker_id'=>$sticker
					)
				)
			);
			if( isset( $data->error ) ) {
				return $this->error( $data );
			}
			return $data->response;
	}
	public function setOnline() {
			$data = json_decode(
				$this->execute(
					'account.setOnline',
					array(
						'voip'=>0
					)
				)
			);
			if( isset( $data->error ) ) {
				return $this->error( $data );
			}
			return 1;
	}
	public function getPosts($filter, $count){
		//фильтр - определяет какие записи выбрать, например от имени сообщества
		//count - определяет кол-во записей котоыре необходимо выбрать
		$data = json_decode(
				$this->execute(
					'wall.get',
					array(
						'owner_id' => $this->group_id,
						'filter' => $filter,
						'count' =>$count
					)
				)
			);
		if( isset( $data->error ) ) {
				return $this->error( $data );
			}
			return $data->response;
	}
	public function searchPost($query, $count){
		//query-поисковый запрос - строка
		$data = json_decode(
			$this->execute(
				'wall.search',
				array(
					'owner_id' => $this->group_id,
					'owners_only' => 1,//возвр только записи владельца стены
					'count' =>$count,
					'query'=>$query//колво запросов
				)
			)
		);
		if( isset( $data->error ) ) {
			return $this->error( $data );
		}
		return $data->response;
	}
	public function editPost($post_id, $message){
		$data = json_decode(
			$this->execute(
				'wall.edit',
				array(
					'owner_id' => $this->group_id,
					'post_id'=>$post_id,
					'message'=>$message
				)
			)
		);
		if( isset( $data->error ) ) {
			return $this->error( $data );
		}
		return 1;
	}
	public function deletePost($post_id){
		$data = json_decode(
			$this->execute(
				'wall.delete',
				array(
					'owner_id' => $this->group_id,
					'post_id'=>$post_id
				)
			)
		);
		if( isset( $data->error ) ) {
			return $this->error( $data );
		}
		return 1;
	}
	public function create_album( $name, $desc ) {
		$data = json_decode(
			$this->execute(
				'photos.createAlbum',
				array(
					'title' => $name,
					'gid' => $this->group_id,
					'description' => $desc,
					'comment_privacy' => 1,
					'privacy' => 1
				)
			)
		);
		if( isset( $data->error ) ) {
			return $this->error( $data );
		}
		return $data->response->aid;
	}
	public function get_album_size( $id ) {
		$data = json_decode(
			$this->execute(
				'photos.getAlbums',
				array(
					'oid' => $this->group_id,
					'aids' => $id
				)
			)
		);
		if( isset( $data->error ) ) {
			return $this->error( $data );
		}
		return $data->response['0']->size;
	}
	public function upload_photo( $file, $album_id, $desc ) {
		if( !is_file( $file ) ) return false;
		$data = json_decode(
			$this->execute(
				'photos.getUploadServer',
				array(
					'aid' => $album_id,
					'gid' => $this->group_id,
					'save_big' => 1
				)
			)
		);
		if( isset( $data->error ) ) {
			return $this->error( $data );
		}
		$err['photos.getUploadServer'] = $data;
		$ch = curl_init( $data->response->upload_url );
		curl_setopt ( $ch, CURLOPT_HEADER, false );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, array( 'file1' => '@' . $file ) );
		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode( $data );
		if( isset( $data->error ) ) {
			return $this->error( $data );
		}
		$err['UploadPhoto'] = $data;
		$data = json_decode(
			$this->execute(
				'photos.save',
				array(
					'aid' => $album_id,
					'gid' => $this->group_id,
					'server' => $data->server,
					'photos_list' => $data->photos_list,
					'hash' => $data->hash,
					'caption' => $desc
				)
			)
		);
		if( isset( $data->error ) ) {
			$err['photos.save'] = $data;
			return $this->error( $err );
		}
		return $data->response['0']->pid;
	}
	private function execute( $method, $params ) {
		$ch = curl_init( 'https://api.vk.com/method/' . $method . '?access_token=' . $this->token );
		curl_setopt ( $ch, CURLOPT_HEADER, false );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $params );
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	private function error( $data ) {
		var_dump($data);
		return false;
	}
}
?>