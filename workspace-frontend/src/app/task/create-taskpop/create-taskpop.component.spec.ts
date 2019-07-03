import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CreateTaskpopComponent } from './create-taskpop.component';

describe('CreateTaskpopComponent', () => {
  let component: CreateTaskpopComponent;
  let fixture: ComponentFixture<CreateTaskpopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CreateTaskpopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CreateTaskpopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
