import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AsTaskComponent } from './as-task.component';

describe('AsTaskComponent', () => {
  let component: AsTaskComponent;
  let fixture: ComponentFixture<AsTaskComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AsTaskComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AsTaskComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
