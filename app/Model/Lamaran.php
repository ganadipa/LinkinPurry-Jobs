<?

namespace App\Model;
use App\Util\Enum\StatusLamaranEnum;

class Lamaran {
    // key
    public int $lamaran_id;

    // foreign key to user (user_id)
    public int $user_id;

    // foreign key to lowongan (lowongan_id)
    public int $lowongan_id;

    public string $cv_path;
    public string $video_path;
    public StatusLamaranEnum $status;
    public string $status_reason;
    public DateTime $created_at;

    public function __construct(int $user_id, int $lowongan_id, string $cv_path, string $video_path, StatusLamaranEnum $status, string $status_reason, DateTime $created_at, int $lamaran_id = null) {
        $this->lamaran_id = $lamaran_id;
        $this->user_id = $user_id;
        $this->lowongan_id = $lowongan_id;
        $this->cv_path = $cv_path;
        $this->video_path = $video_path;
        $this->status = $status;
        $this->status_reason = $status_reason;
        $this->created_at = $created_at;
    }

}