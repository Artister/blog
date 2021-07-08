<?php

namespace Application\Models;

use DevNet\Entity\EntityContext;
use DevNet\Entity\EntityModelBuilder;
use DevNet\Entity\EntityOptions;
use DevNet\Entity\EntitySet;

class DbManager extends EntityContext
{
   private EntitySet $Authors;
   private EntitySet $Sections;
   private EntitySet $Posts;
   private EntitySet $Comments;

   public function __construct(EntityOptions $options)
   {
      parent::__construct($options);
      $this->Authors  = $this->set(Author::class);
      $this->Sections = $this->set(Section::class);
      $this->Posts    = $this->set(Post::class);
      $this->Comments = $this->set(Comment::class);
      $this->Users = $this->set(User::class);
   }

   public function __get(string $name)
   {
      return $this->$name;
   }

   public function onModelCreate(EntityModelBuilder $builder)
   {
      $builder->entity(User::class)
         ->hasOne('Author', Author::class);

      $builder->entity(Author::class)
         ->hasForeignKey('UserId', User::class)
         ->hasOne('User', User::class)
         ->hasMany('Posts', Post::class);

      $builder->entity(Section::class)
         ->hasMany('Posts', Post::class);

      $builder->entity(Post::class)
         ->hasForeignKey('SectionId', Section::class)
         ->hasForeignKey('AuthorId', Author::class)
         ->hasOne('Section', Section::class)
         ->hasOne('Author', Author::class)
         ->hasMany('Comments', Comment::class);

      $builder->entity(Comment::class)
         ->hasForeignKey('PostId', Post::class)
         ->hasForeignKey('AuthorId', Author::class)
         ->hasOne('Post', Post::class)
         ->hasOne('Author', Author::class);
   }
}
