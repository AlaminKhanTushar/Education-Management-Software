<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    private static $student;
    private static $image;
    private static $imageName;
    private static $imageUrl;
    private static $directory;

    public static function getImageUrl($request)
    {
        self::$image = $request->file('image');
        self::$imageName = self::$image->getClientOriginalName();
        self::$directory = 'student-images/';
        self::$image->move(self::$directory, self::$imageName);
        self::$imageUrl = self::$directory.self::$imageName;
        return self::$imageUrl;
    }

    public static function newStudent($request, $code)
    {
        self::$student          = new Student();
        self::$student->name    = $request->name;
        self::$student->code    = $code;
        self::$student->email   = $request->email;
        self::$student->password= bcrypt($request->mobile);
        self::$student->mobile  = $request->mobile;
        self::$student->address = $request->address;
        self::$student->image   = self::getImageUrl($request);
        self::$student->save();
    }

    public static function updateStudent($request, $id, $code)
    {
        self::$student = Student::find($id);
        if ($request->file('image')) {
            if (file_exists(self::$student->image)) {
                unlink(self::$student->image);
            }
            self::$imageUrl = self::getImageUrl($request);
        } else {
            self::$imageUrl = self::$student->image;
        }
        self::$student->name = $request->name;
        self::$student->code = $code;
        self::$student->email = $request->email;
        self::$student->password = bcrypt($request->mobile);
        self::$student->mobile = $request->mobile;
        self::$student->address = $request->address;
        self::$student->image = self::$imageUrl;
        self::$student->status = $request->status;
        self::$student->save();
    }
}

