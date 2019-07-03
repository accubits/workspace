<?php
/**
 * Created by PhpStorm.
 * User: reshman
 * Date: 23/03/18
 * Time: 12:25 PM
 */

namespace Modules\TaskManagement\Repositories\Comment;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\ResponseStatus;
use Modules\Common\Utilities\Utilities;
use Modules\TaskManagement\Entities\Task;
use Modules\TaskManagement\Entities\TaskComments;
use Modules\TaskManagement\Entities\TaskCommentsLike;
use Modules\TaskManagement\Http\Requests\TaskCommentRequest;
use Modules\TaskManagement\Repositories\CommentRepositoryInterface;
use Modules\TaskManagement\Transformers\TaskCommentList;
use Modules\TaskManagement\Transformers\TaskCommentResource;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;

class CommentRepository implements CommentRepositoryInterface
{
    protected $s3BasePath;
    protected $content
    ;
    public function __construct()
    {
        $this->content = array();
        $this->s3BasePath = env('S3_PATH');
    }

    /**
     *
     * {
    "description" : "seeeccond My first Comment",
    "task_slug" : "1177803675acf32a2a5672",
    "reply": {
    "comment_slug" : "21190159085ad3293182860"
    }

    }
     * @param Request $request
     * @return array
     */
    public function addComment(TaskCommentRequest $request)
    {
        DB::beginTransaction();
        try {
            $user    = $this->getUser();
            $task    = $this->getTaskId($request->task_slug);
            $comment = new TaskComments;
            $comment->{TaskComments::slug}        = Utilities::getUniqueId();
            $comment->{TaskComments::description} = $request->description;
            $comment->{TaskComments::task_id}     = $task->id;
            $comment->{TaskComments::user_id}     = $user->id;

            if ($request->has('reply_comment_slug') && $request->reply_comment_slug)
                $comment->{TaskComments::parent_comment_id}  = $this->getCommentId($request->reply_comment_slug)->id;

            $comment->save();
            DB::commit();
            return $this->content = array(
                'data'   => array('message' => "Comments added successfully!"),
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->content['error']   =  $ex->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
        }

        return $this->content;
    }

    public function taskCommentDelete(Request $request)
    {
        $taskComments = TaskComments::where(TaskComments::slug, $request->commentSlug)->first();

        try {
            if (!$taskComments) {
                throw new \Exception("Invalid Comment", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $taskComments->delete();

            $this->content = array(
                'data'   => array('message' => "Comment deleted successfully!"),
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );

        } catch (ModelNotFoundException $e) {
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
        } catch (\Exception $ex) {
            $this->content['error']   =  $ex->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
        }

        return $this->content;
    }

    /**
     * {
    "like" : true
    }
     * @param Request $request
     * @return array
     */
    public function like(Request $request)
    {
        DB::beginTransaction();
        try {
            $user    = $this->getUser();
            $comment = $this->getCommentId($request->comment_id);
            $likeStatus = 'Unliked';
            if ($request->like) {
                $this->addLike($comment, $user, $request);
                $likeStatus = 'Liked';
            } else {
                $this->removeLike($comment, $user);
            }

            DB::commit();

            return $this->content = array(
                'data'   => array('message' => "Comment {$likeStatus}"),
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->content['error']   =  $ex->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
        }

        return $this->content;
    }

    public function addLike($comment, $user, $request)
    {
        TaskCommentsLike::firstOrCreate(
            [ TaskCommentsLike::comment_id  => $comment->id, TaskCommentsLike::user_id     => $user->id ],
            [TaskCommentsLike::like => 1 ]
        );
        return true;
    }

    public function removeLike($comment, $user)
    {
        TaskCommentsLike::where(TaskCommentsLike::comment_id, $comment->id)
            ->where(TaskCommentsLike::user_id, $user->id)
            ->delete();
        return true;
    }

    public function listAll($task)
    {
        $me     = Auth::user();
        $status = ResponseStatus::OK;
        $task = $this->getTaskId($task);

        $taskComments = DB::table(TaskComments::table)
            ->select(
                TaskComments::table. '.id',
                TaskComments::table. '.' .TaskComments::slug,
                'parentComment.' .TaskComments::slug. ' as taskParentCommentSlug',
                TaskComments::table. '.' .TaskComments::description,
                TaskComments::table. '.' .TaskComments::CREATED_AT. ' as createdOn',
                DB::raw("count(tm_task_comments_like.id) as like_count"),
                TaskComments::table. '.' .TaskComments::parent_comment_id,
                User::table. '.' .User::name. ' as userName',
                User::table. '.' .User::slug. ' as userSlug',
                DB::raw('concat("'.$this->s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as userImage')
            )
            ->leftjoin(TaskCommentsLike::table, TaskComments::table. '.id', '=', TaskCommentsLike::table. '.'. TaskCommentsLike::comment_id)
            ->leftjoin(TaskComments::table.' AS parentComment', 'parentComment.id', '=', TaskComments::table. '.' .TaskComments::parent_comment_id)
            ->leftjoin(User::table, User::table. '.id', '=', TaskComments::table. '.'. TaskComments::user_id)
            ->leftjoin(UserProfile::table, User::table. '.id', '=', UserProfile::table. '.' .UserProfile::user_id)
            ->where(TaskComments::table. '.' .TaskComments::task_id, $task->id)
            ->groupBy(TaskComments::table. '.id')
            ->get();

        $commentIdArr = $taskComments->pluck('id')->toArray();


        $taskCommentsLike = DB::table(TaskCommentsLike::table)
            ->select(
                User::table. '.id as userId',
                User::table. '.' .User::name,
                TaskCommentsLike::table. '.' .TaskCommentsLike::comment_id
            )
            ->leftjoin(User::table, User::table. '.id', '=', TaskCommentsLike::table. '.'. TaskCommentsLike::user_id)
            ->whereIn(TaskCommentsLike::table. '.' .TaskCommentsLike::comment_id, $commentIdArr)
            ->get();

        $taskCommentLikeGroup = $taskCommentsLike->groupBy(TaskCommentsLike::comment_id)
            ->toArray();

        $taskComments->map(function ($taskComments) use ($taskCommentLikeGroup, $me) {
            if (isset($taskCommentLikeGroup[$taskComments->id])) {

                if (collect($taskCommentLikeGroup[$taskComments->id])->pluck('userId')->contains($me->id))
                    $taskComments->like['meLiked'] = true;
                else
                    $taskComments->like['meLiked'] = false;

                $taskComments->like['likeCount'] = $taskComments->like_count;
                $taskComments->like['likers'] = collect($taskCommentLikeGroup[$taskComments->id])->pluck('name');
            } else{
                $taskComments->like = [];
            }
        });

        if ($taskComments->isEmpty())
            $status = ResponseStatus::NOT_FOUND;

        $response = new TaskCommentList($taskComments);
        $this->content['data'] = $response;
        $this->content['code'] = Response::HTTP_OK;
        $this->content['status'] = $status;
        return $this->content;
    }

    /**
     * fetch one comment
     * @param Request $request
     * @return array
     */
    public function fetchComment(Request $request)
    {
        try {
            $taskComments = DB::table(TaskComments::table)->where(TaskComments::slug, $request->commentSlug)
                ->select(
                    TaskComments::slug,
                    TaskComments::description. ' as message'
                    )
                ->first();

            if (empty($taskComments)) {
                throw new \Exception("request is invalid", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return $this->content = array(
                'data'   => $taskComments,
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );

        } catch (ModelNotFoundException $e) {
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
        } catch (\Exception $ex) {
            $this->content['error']   =  array('msg' => $ex->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
        }

        return $this->content;
    }

    public function updateComment(Request $request)
    {
        try {
            DB::beginTransaction();


            $taskCommentsQuery = TaskComments::where(TaskComments::slug, $request->commentSlug)
                ->select(
                    TaskComments::slug,
                    TaskComments::description
                );

            $taskComments = $taskCommentsQuery->first();

            if (empty($taskComments)) {
                throw new \Exception("request is invalid", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $taskCommentsQuery->update([TaskComments::description => $request->commentMessage]);
            DB::commit();

            return $this->content = array(
                'data'   => array('msg' => 'message updated'),
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $ex->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
        }

        return $this->content;
    }

    private function getTaskId($slug)
    {
        return Task::where(Task::slug, $slug)->firstOrFail();
    }

    private function getCommentId($slug)
    {
        return TaskComments::where(TaskComments::slug, $slug)->firstOrFail();
    }

    private function getUser()
    {
        return Auth::user();
    }


}