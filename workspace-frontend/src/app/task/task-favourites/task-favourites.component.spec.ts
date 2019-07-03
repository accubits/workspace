import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TaskFavouritesComponent } from './task-favourites.component';

describe('TaskFavouritesComponent', () => {
  let component: TaskFavouritesComponent;
  let fixture: ComponentFixture<TaskFavouritesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TaskFavouritesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TaskFavouritesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
