import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NewDeptComponent } from './new-dept.component';

describe('NewDeptComponent', () => {
  let component: NewDeptComponent;
  let fixture: ComponentFixture<NewDeptComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NewDeptComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NewDeptComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
