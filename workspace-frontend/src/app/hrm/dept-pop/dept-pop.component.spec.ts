import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DeptPopComponent } from './dept-pop.component';

describe('DeptPopComponent', () => {
  let component: DeptPopComponent;
  let fixture: ComponentFixture<DeptPopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DeptPopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DeptPopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
