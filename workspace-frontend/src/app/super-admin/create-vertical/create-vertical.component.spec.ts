import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CreateVerticalComponent } from './create-vertical.component';

describe('CreateVerticalComponent', () => {
  let component: CreateVerticalComponent;
  let fixture: ComponentFixture<CreateVerticalComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CreateVerticalComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CreateVerticalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
