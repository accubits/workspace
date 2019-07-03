import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TaskActiveComponent } from './task-active.component';

describe('TaskActiveComponent', () => {
  let component: TaskActiveComponent;
  let fixture: ComponentFixture<TaskActiveComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TaskActiveComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TaskActiveComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
