import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SecTaskComponent } from './sec-task.component';

describe('SecTaskComponent', () => {
  let component: SecTaskComponent;
  let fixture: ComponentFixture<SecTaskComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SecTaskComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SecTaskComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
