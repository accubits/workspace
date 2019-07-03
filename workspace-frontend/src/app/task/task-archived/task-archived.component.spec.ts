import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TaskArchivedComponent } from './task-archived.component';

describe('TaskArchivedComponent', () => {
  let component: TaskArchivedComponent;
  let fixture: ComponentFixture<TaskArchivedComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TaskArchivedComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TaskArchivedComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
