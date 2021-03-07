<?php

namespace Application\Models;

use Artister\Entity\EntityContext;
use Artister\Entity\EntityModelBuilder;
use Artister\Entity\EntityOptions;
use Artister\Entity\EntitySet;

class DbManager extends EntityContext
{
   private EntitySet $Authors;
   private EntitySet $Sections;
   private EntitySet $Postes;
   private EntitySet $Comments;

   public function __construct(EntityOptions $options)
   {
      parent::__construct($options);
      $this->Profiles = $this->set(Author::class);
      $this->Profiles = $this->set(Section::class);
      $this->Profiles = $this->set(Post::class);
      $this->Comments = $this->set(Comment::class);
   }

   public function __get(string $name)
   {
      return $this->$name;
   }

   public function onModelCreate(EntityModelBuilder $builder)
   {
      $builder->entity(Author::class)
      ->hasForeignKey('UserId', User::class)
      ->hasOne('User', User::class)
      ->hasMany('Postes', Post::class);
      
      $builder->entity(Section::class)
         ->hasMany('Postes', Post::class);

      $builder->entity(Post::class)
         ->hasForeignKey('SectionId', Section::class)
         ->hasForeignKey('AuthorId', Post::class)
         ->hasOne('Section', Section::class)
         ->hasOne('Author', Author::class)
         ->hasMany('Comments', Comment::class);

      $builder->entity(Comment::class)
         ->hasForeignKey('PostId', Post::class)
         ->hasForeignKey('AuthorId', Post::class)
         ->hasOne('Post', Post::class)
         ->hasOne('Author', Author::class);
   }
}
