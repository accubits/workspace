import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TaskListingComponent } from './task-listing.component';

describe('TaskListingComponent', () => {
  let component: TaskListingComponent;
  let fixture: ComponentFixture<TaskListingComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TaskListingComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TaskListingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
